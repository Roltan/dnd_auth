<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function register(RegisterRequest $request): Response
    {
        return $this->authService->register($request);
    }

    public function login(LoginRequest $request): Response
    {
        return $this->authService->login($request);
    }

    public function logout(): Response
    {
        return $this->authService->logout();
    }
}
