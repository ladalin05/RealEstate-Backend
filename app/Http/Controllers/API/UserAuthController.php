<?php

namespace App\Http\Controllers\API;

use App\Services\AuthSV;
use Illuminate\Http\Request;
use App\Services\BrevoService;
use App\Services\TelegramService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserManagement\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\StoreUserRequest;
use App\Http\Requests\UserManagement\UpdateUserRequest;
use App\Http\Requests\UserManagement\StoreUserTelegramRegisterRequest;

class UserAuthController extends Controller
{
    protected $AuthSV;
    protected $TelegramSV;
    protected $BrevoSV;

    public function __construct()
    {
        $this->AuthSV     = new AuthSV();
        $this->TelegramSV = new TelegramService();
        $this->BrevoSV    = new BrevoService();
    }

    public function getQuery()
    {
        return User::query();
    }

    public function register(StoreUserRequest $request)
    {
        try {
            $params = $request->validated();
            // Basic email deliverability check before proceeding
            if (empty($params['email']) || !$this->emailLooksDeliverable($params['email'])) {
                return $this->errorResponse('Email domain appears invalid or cannot receive mail', 422);
            }
            // Check if a user already exists with this email
            $existing = User::where('email', $params['email'])->first();
            if ($existing) {
                // If user exists but hasn't verified email, update password & resend OTP
                if (!$existing->is_verify_email) {
                    if (isset($params['password'])) {
                        $existing->password = bcrypt($params['password']);
                    }

                    DB::beginTransaction();
                    // renew OTP
                    $otp = random_int(100000, 999999);
                    $existing->otp = (string) $otp;
                    $existing->otp_expires_at = now()->addMinutes(10);
                    $existing->save();

                    // send OTP
                    try {
                        $html = "<p>Your verification code is: <strong>{$otp}</strong></p>";
                        $resp = $this->BrevoSV->sendSimpleEmail($existing->email, $existing->username ?? 'Customer', 'Your OTP Code', $html);
                        Log::info('Brevo resend OTP response', ['email' => $existing->email, 'response' => $resp]);
                    } catch (\Throwable $e) {
                        Log::error('Failed to resend OTP email', ['email' => $existing->email, 'error' => $e->getMessage()]);
                    }

                    DB::commit();
                    return $this->successResponse([], 'An OTP has been resent to your email. Please verify to complete registration.');
                }

                // Already verified — cannot register again
                return $this->errorResponse('Email already registered', 422);
            }

            // New user: Hash password before creating user
            if (isset($params['password'])) {
                $params['password'] = bcrypt($params['password']);
            }

            DB::beginTransaction();
            $result = $this->AuthSV->registerUser($params);

            // Generate OTP and attach to user (pending verification)
            $otp = random_int(100000, 999999);
            $result->otp = (string) $otp;
            $result->otp_expires_at = now()->addMinutes(10);
            $result->save();

            // Try to send OTP email (log response; don't abort registration on failure)
            try {
                $html = "<p>Your verification code is: <strong>{$otp}</strong></p>";
                $resp = $this->BrevoSV->sendSimpleEmail($result->email, $result->username ?? 'Customer', 'Your OTP Code', $html);
                Log::info('Brevo send OTP response', ['email' => $result->email, 'response' => $resp]);
            } catch (\Throwable $e) {
                Log::error('Failed to send OTP email', ['email' => $result->email, 'error' => $e->getMessage()]);
            }

            DB::commit();

            $token = Auth::guard('api-user')->login($result);
            if (!$token) {
                return $this->errorResponse('Unable to create token', 500);
            }

            // Do not issue JWT yet — wait for OTP verification
            return $this->successResponse('User registered successfully. Please verify the OTP sent to your email.', ['token' => $token, 'user' => $result]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function verifyOtp(Request $request)
    {
        $payload = $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
        ]);

        $user = User::where('email', $payload['email'])->first();
        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        if (!$user->otp || $user->otp !== $payload['otp']) {
            return $this->errorResponse('Invalid OTP', 422);
        }

        if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
            return $this->errorResponse('OTP expired', 422);
        }

        // Mark verified and clear otp
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->is_verify_email = 1;
        $user->save();

        // Issue JWT
        $token = Auth::guard('api-user')->login($user);
        if (!$token) {
            return $this->errorResponse('Unable to create token', 500);
        }

        return $this->successResponse(['user' => $user, 'token' => $token], 'OTP verified, user logged in');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $result = $this->AuthSV->loginUser($credentials);
            return $this->successResponse('User logged in successfully', $result);
        } catch (\Exception $e) {
            return $this->errorResponse('Invalid credentials', $e->getCode());
        }
    }

    public function telegramRegister(StoreUserTelegramRegisterRequest $request)
    {
        try {
            $params                    = [];
            $params['telegram_id']     = $request->telegram_id;
            $params['username']        = $request->username;
            $params['profile_picture'] = $request->profile_picture;

            DB::beginTransaction();
            $result = $this->AuthSV->userTelegramRegister($params);
            $user   = $result['user'];
            $is_new = $result['is_new'];
            DB::commit();

            $token = Auth::guard('api-user')->login($user);

            if (!$token) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Send a welcome message only to brand-new Telegram users
            if ($is_new) {
                try {
                    $resp = $this->TelegramSV->sendMessage(
                        $user->telegram_id,
                        "Welcome, {$user->username}! Your account has been created successfully."
                    );
                    Log::info('Telegram welcome message response', ['telegram_id' => $user->telegram_id, 'response' => $resp]);
                } catch (\Throwable $e) {
                    Log::error('Failed to send Telegram welcome message', ['telegram_id' => $user->telegram_id, 'error' => $e->getMessage()]);
                }
            }

            $message = $is_new ? 'User registered successfully' : 'User logged in successfully';
            return $this->successResponse(["user" => $user, "token" => $token, "is_new" => $is_new], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Refresh Token
    public function refreshToken()
    {
        try {
            $role = 'user';
            $token = $this->AuthSV->refreshToken($role);
            return $this->successResponse($token, 'Token refreshed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function logout()
    {
        try {
            $this->AuthSV->logout('user');
            return $this->successResponse('User logged out successfully', []);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Lightweight check whether an email's domain likely accepts mail.
     * Uses filter_var and MX/A DNS lookup. Not 100% reliable but prevents obvious typos.
     */
    private function emailLooksDeliverable(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $domain = substr(strrchr($email, "@"), 1);
        if (!$domain) {
            return false;
        }

        if (function_exists('checkdnsrr')) {
            if (checkdnsrr($domain, 'MX')) {
                return true;
            }
            if (checkdnsrr($domain, 'A')) {
                return true;
            }
        }

        if (function_exists('dns_get_record')) {
            $records = @dns_get_record($domain, DNS_MX);
            if (!empty($records)) {
                return true;
            }
            $a = @dns_get_record($domain, DNS_A);
            if (!empty($a)) {
                return true;
            }
        }

        return false;
    }

    public function getInfo() {
        try {
            $user = Auth::guard('api-user')->user();
            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }
            return $this->successResponse('User info fetched successfully.',['user' => $user]);
        } catch (\Throwable $th) {
            return $this->errorResponse('Failed to fetch user info.', 500);
        }
    }

    public function updateInfo(UpdateUserRequest $request)
    {
        /** @var User $user */
        $user = Auth::guard('api-user')->user();

        if (!$user) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $data = $request->validated();

        // Belt-and-braces: this endpoint never touches these, no matter what request class is used
        unset(
            $data['password'],
            $data['profile_picture'],
            $data['google_id'],
            $data['telegram_id'],
            $data['telegram_username'],
            $data['is_verify_google'],
            $data['is_verify_telegram'],
            $data['is_verify_email'],
            $data['is_verify_phone'],
            $data['active'],
        );

        DB::beginTransaction();

        try {
            $user->fill($data);
            $user->save();

            DB::commit();

            return $this->successResponse(['user' => $user->fresh()], 'Profile updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->errorResponse('Failed to update profile.', 500);
        }
    }
}