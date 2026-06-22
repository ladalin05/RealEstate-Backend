<?php

namespace App\Services;

use Exception;
use App\Models\UserManagement\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Services\BaseService;

class AuthSV extends BaseService
{

    // use BaseService;
    public function getQuery()
    {
        return User::query();
    }
   /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */

   public function registerUser($data){
       try {
           $query = $this->getQuery();
           $user = $query->create($data);
           return $user;
       } catch (Exception $e) {
           throw new Exception('Error creating user: ' . $e->getMessage());
       }
   }

   public function loginUser($data){
       try {
           $credentials = [
               'email' => $data['email'],
               'password' => $data['password'],
           ];
           // Query user in database 
           $user = User::where('email', $data['email'])->first();

           if (!$user) {
               throw new Exception('User not found');
           }

           if (!Hash::check($data['password'], $user->password)) {
               throw new Exception('Invalid credentials', $e->getCode());
           }

           $token = Auth::guard('api-user')->setTTL(60 * 24 * 90)->login($user);
           if (!$token) {
                throw new Exception('Unauthorized',  $e->getCode());
           }

           return [
               'user' => $user,
               'token' => $token
           ];
       } catch (Exception $e) {
           throw new Exception('Error logging in user: ' . $e->getMessage());
       }
   }

    public function userTelegramRegister($data)
    {
        try {
            $query        = $this->getQuery();
            $existingUser = User::where("telegram_id", $data['telegram_id'])->first();
            if($existingUser){
                // dd($existingUser);
                return [
                    'user'   => $existingUser,
                    'is_new' => false,
                ];
            }
            $hash = bin2hex(random_bytes(8)); // random string
            $user = $query->create([
                'username'        => $data['username'],
                'telegram_id'     => $data['telegram_id'],
                'profile_picture' => $data['profile_picture'],
                'hash'            => $hash,
                'password'        => base64_encode($hash)       // you save the password like this
            ]);
            return [
                'user'   => $user,
                'is_new' => true,
            ];
        } catch (Exception $e) {
            throw new Exception('Error creating user: ' . $e->getMessage());
        }
    }

   public function GetProfile($role)
   {
        try {
            # Here we just get information about current user
            if ($role == 'admin') {
                return response()->json(Auth::guard('api')->user());
            }
            return response()->json(Auth::guard('api-user')->user());
        }  catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        }
   }

   public function logout()
   {
        try {
            Auth::guard('api-user')->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error logging out'], 500);
        }
   }

   public function refreshToken($role)
   {
       if ($role == 'admin') {

           return Auth::guard('api')->setTTL( config('jwt.refresh_ttl'))->refresh(true);
       }
   
       // Same for user
       return Auth::guard('api-user')->setTTL( config('jwt.refresh_ttl'))->refresh(true);
   }
}
