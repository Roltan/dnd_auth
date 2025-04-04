<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        protected AuthServices $authServices
    ) {}

    public function register(RegisterRequest $request): Response
    {
        return $this->authServices->register($request);
    }

    public function login(LoginRequest $request): Response
    {
        return $this->authServices->login($request);
    }

    public function logout(): Response
    {
        return $this->authServices->logout();
    }
}
