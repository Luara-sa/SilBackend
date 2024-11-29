<?php

namespace App\Repositories;

interface AuthRepositoryInterface
{
    public function register(array $data);
    public function login(array $credentials);

    public function logout();

    public function forgotPassword(string $data);

    public function resetPassword(array $data);
}
