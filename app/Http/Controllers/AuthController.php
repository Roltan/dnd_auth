<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        protected AuthServices $authServices
    )
    {
    }

    public function login(LoginRequest $request): Response
    {
        return $this->authServices->login($request);
    }
}
