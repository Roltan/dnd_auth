<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ResetRequest;
use App\Services\PasswordService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PasswordController extends Controller
{
    public function __construct(
        private PasswordService $passwordService
    )
    {
    }

    public function forgot(EmailRequest $request): Response
    {
        return $this->passwordService->forgot($request);
    }

    public function viewReset(ResetRequest $request): Response
    {
        return $this->passwordService->viewReset($request);
    }

    public function change(PasswordRequest $request): Response
    {
        return $this->passwordService->change($request);
    }
}
