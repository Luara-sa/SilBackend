<?php


namespace App\Repositories;

use App\Models\User;
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
    public function forgotPassword(string $email)
    {
        // Send a password reset link to the provided email address
        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            return [
                'message' => __('messages.password_reset_link')
            ];
        }

        return [
            'message' => __('messages.password_reset_error')
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

}
