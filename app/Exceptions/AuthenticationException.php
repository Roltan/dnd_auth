<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationException extends Exception
{
    protected array $additionalData = [];

    public function __construct(
        string $message = 'Unauthorized',
        int $code = 403,
        array $arr = []
    ) {
        parent::__construct($message, $code);
        $this->additionalData = $arr;
    }

    public function render(Request $request): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $this->getMessage(),
        ];

        // Если есть дополнительные данные, добавляем их в ответ
        if (!empty($this->additionalData))
            $response = array_merge($response, $this->additionalData);

        return response()->json($response, $this->getCode());
    }
}
