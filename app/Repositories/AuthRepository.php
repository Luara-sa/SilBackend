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
        $data['password'] = Hash::make($data['password']);

        $user= User::create($data);
        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ];
    }

    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return false;
        }
        // Get authenticated user
        $user = Auth::user();

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

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
        $status = Password::reset(
            $credentials,
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return [
                'message' => __('messages.password_reset_successful')
            ];
        }

        return [
            'message' => __('messages.password_reset_error')
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
            return response()->jsonResponse(
                false,
                __('messages.invalid_reset_code'),
                null,
                400
            );
        }

        // Update the user's password
        $user = User::where('email', $data['email'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();

        // Delete the reset code
        DB::table('password_reset_codes')->where('email', $data['email'])->delete();


        return [
            'status' => true,
            'message' => __('messages.password_reset_successful'),
        ];
    }
}