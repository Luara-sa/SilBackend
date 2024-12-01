<?php

namespace App\Repositories;

interface AuthRepositoryInterface
{
    public function register(array $data);
    public function login(array $credentials);

    public function logout();

    public function forgotPassword(array $data);

    public function resetPassword(array $data);
    public function verifyResetCode(array $data);
}
