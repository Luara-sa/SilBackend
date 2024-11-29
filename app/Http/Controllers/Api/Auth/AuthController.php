<?php

namespace App\Http\Controllers\Api\Auth;



use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $response = $this->authRepository->forgotPassword($request->email);

        return response()->jsonResponse(
            true,
            $response['message'],
            null,
            200
        );
    }
}
