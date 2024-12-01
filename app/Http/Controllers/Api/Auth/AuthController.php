<?php

namespace App\Http\Controllers\Api\Auth;



use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authRepository->register($request->validated());

        return response()->jsonResponse(
            true,
            __('messages.user_registered'),
            $data,
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authRepository->login($request->validated());

        if (!$data) {
            return response()->jsonResponse(
                false,
                __('messages.invalid_credentials'),
                null,
                401
            );
        }

        return response()->jsonResponse(
            true,
            __('messages.login_successful'),
            $data,
            200
        );
    }


    /**
     * User Logout
     */
    public function logout()
    {
        $response = $this->authRepository->logout();

        return response()->jsonResponse(
            true,
            $response['message'],
            null,
            200
        );
    }

    /**
     * Forgot Password
     */
    public function forgotPassword(Request $request)
    {
        $response = $this->authRepository->forgotPassword($request->all());

        return response()->jsonResponse(
            true,
            $response['message'],
            null,
            200
        );
    }


    // Redirect to Social Provider
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    // Handle Provider Callback
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            $user = $this->authRepository->findOrCreateSocialUser($socialUser, $provider);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->jsonResponse(
                true,
                __('auth.social_login_success'),
                ['user' => $user, 'token' => $token],
                200
            );
        } catch (\Exception $e) {
            return response()->jsonResponse(
                false,
                __('auth.social_login_failed'),
                ['error' => $e->getMessage()],
                500
            );
        }
    }

    public function verifyResetCode(Request $request)
    {
        $response = $this->authRepository->verifyResetCode($request->all());
 if ($response['status'] == false) {
            return response()->jsonResponse(
                false,
                $response['message'],
                null,
                400
            );
        }
        return response()->jsonResponse(
            true,
            $response['message'],
            null,
            200
        );



    }

    // reset password

    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->authRepository->resetPassword($request->all());
     if ($response['status'] == false) {
            return response()->jsonResponse(
                false,
                $response['message'],
                null,
                400
            );
        }
        return response()->jsonResponse(
            true,
            $response['message'],
            null,
            200
        );
    }


}
