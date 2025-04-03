<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\RefreshRequest;
use App\Http\Requests\RegRequest;
use App\Services\AuthServices;
use App\Services\PasswordService;
use App\Services\UserServices;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        public AuthServices $authServices,
        public PasswordService $passwordService,
        public UserServices $userServices,
    ) {}

    public function register(RegRequest $regRequest): Response
    {
        return $this->authServices->register($regRequest);
    }

    public function login(LoginRequest $loginRequest): Response
    {
        return $this->authServices->login($loginRequest);
    }

    public function logout(): Response
    {
        return $this->authServices->logout();
    }

    public function refresh(RefreshRequest $request): Response
    {
        return $this->authServices->refresh($request);
    }

    public function forgot(EmailRequest $emailRequest): Response
    {
        return $this->passwordService->forgot($emailRequest);
    }

    public function viewResetPassword(Request $request): Response
    {
        return $this->passwordService->viewResetPassword($request);
    }

    public function changePassword(PasswordRequest $request): Response
    {
        return $this->passwordService->changePassword($request);
    }

    public function edit(EditRequest $editRequest): Response
    {
        return $this->userServices->edit($editRequest);
    }

    public function info(): Response
    {
        return $this->userServices->info();
    }
}
