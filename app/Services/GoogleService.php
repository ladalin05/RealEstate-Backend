<?php

namespace App\Services;

use App\Models\UserManagement\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Exception;

class GoogleService
{
    public static function googleLogin(string $accessToken): array
    {
        $googleRes = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v1/userinfo', [
                'alt' => 'json',
            ]);

        if ($googleRes->failed()) {
            throw new Exception('Invalid Google access token');
        }

        $googleData = $googleRes->json();

        $googleId = $googleData['id'] ?? null;
        $name     = $googleData['name'] ?? 'Unknown';
        $email    = $googleData['email'] ?? null;
        $profilePicture = $googleData['picture'] ?? null;

        if (!$googleId) {
            throw new Exception('Unable to retrieve Google user info');
        }

        $user = User::where('google_id', $googleId)->first();

        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            $user->google_id = $googleId;
            $user->is_verify_google = 1;
            if (!$user->email && $email) {
                $user->email = $email;
            }
            if ($profilePicture) {
                $user->profile_picture = $profilePicture;
            }
            $user->save();
        } else {
            $user = User::create([
                'name'             => $name,
                'username'         => self::generateUniqueUsername($name),
                'email'            => $email,
                'google_id'        => $googleId,
                'profile_picture'  => $profilePicture,
                'is_verify_google' => 1,
                'is_verify_email'  => $email ? 1 : 0,
            ]);
        }

        $token = self::generateToken($user->id);

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    private static function generateUniqueUsername(string $name): string
    {
        $base = Str::slug($name) ?: 'user';
        $username = $base . '-' . strtolower(Str::random(5));

        while (User::where('username', $username)->exists()) {
            $username = $base . '-' . strtolower(Str::random(5));
        }

        return $username;
    }

    private static function generateToken(string $userId): string
    {
        $payload = [
            'id'  => $userId,
            'iat' => time(),
            'exp' => time() + (7 * 24 * 60 * 60), // 7 days
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }
}