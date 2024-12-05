<?php


namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        // Hash the password before storing it
        $data['password'] = Hash::make($data['password']);

        // Create the user
        $user = User::create($data);

        // Generate a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Update the FCM token in the corresponding token record if provided
        if (!empty($data['fcm_token'])) {
            $user->tokens()->latest('id')->first()->update(['fcm_token' => $data['fcm_token']]);
        }

        return [
            'user' => $user,
            'token' => $token,
        ];
    }


    public function login(array $credentials)
    {
        // Attempt to authenticate with the provided credentials
        if (!Auth::attempt($credentials)) {
            return false;
        }

        // Get authenticated user
        $user = Auth::user();

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        // If FCM token is provided, update it in the corresponding token record
        if (!empty($credentials['fcm_token'])) {
            $user->tokens()->latest('id')->first()->update(['fcm_token' => $credentials['fcm_token']]);
        }

        return [
            'user' => $user,
            'token' => $token,
        ];
    }


    /**
     * Handle user logout.
     */
    public function logout()
    {
        // Get the current authenticated user and delete their token
        Auth::user()->currentAccessToken()->delete();

        // Return a success response
        return [
            'message' => 'Logout successful',
        ];
    }



    /**
     * Handle forgot password functionality.
     */
    public function forgotPassword(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return [
                'status' => false,
                'message' => __('messages.email_not_found'),
            ];
        }

        // Generate a unique code
        $code = rand(100000, 999999);

        // Store the code in the database
        \DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $user->email],
            ['code' => $code, 'created_at' => now()]
        );

        // Send the code via email
        \Mail::to($user->email)->send(new \App\Mail\PasswordResetCode($code));

        return [
            'status' => true,
            'message' => __('messages.reset_code_sent'),
        ];
    }

    /**
     * Handle reset password functionality.
     */

    public function resetPassword(array $credentials){
        // Reset the user's password
       $user = User::where('email', $credentials['email'])->first();
       if (!$user){
              return [
                'message' => __('messages.password_reset_error')
              ];
       }
         $user->password = Hash::make($credentials['password']);
            $user->save();

        return [
            'message' => __('messages.password_reset_success')
        ];
    }


    public function findOrCreateSocialUser($socialUser, $provider)
    {
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(uniqid()), // Set a random password
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        }

        return $user;
    }

    public function verifyResetCode(array $data)
    {

        $resetRecord = DB::table('password_reset_codes')->where('email', $data['email'])->first();

        if (!$resetRecord || $resetRecord->code != $data['code']) {
            return [
                'status' => false,
                'message' => __('messages.invalid_reset_code'),

            ];

        }


        // Delete the reset code
        DB::table('password_reset_codes')->where('email', $data['email'])->delete();


        return [
            'status' => true,
            'message' => __('messages.code_verified'),
        ];
    }
}
