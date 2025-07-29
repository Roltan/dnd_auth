<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="auth",
 *     version="1.0.0",
 *     description="API documentation for my Laravel project"
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 *
 * )
 * @OA\Components(
 *      @OA\Schema(
 *          schema="ValidationErrorResponse",
 *          title="ValidationErrorResponse",
 *          @OA\Property(property="status", type="bool", example="false"),
 *          @OA\Property(property="errors", type="object",
 *              @OA\AdditionalProperties(
 *                  type="array",
 *                  @OA\Items(type="string", example="The email has already been taken.")
 *              )
 *          ),
 *          @OA\Property(property="message", type="string", example="The given data was invalid.")
 *      ),
 *      @OA\Schema(
 *          schema="AuthenticationErrorResponse",
 *          title="AuthenticationErrorResponse",
 *          @OA\Property(property="status", type="bool", example="false"),
 *          @OA\Property(property="message", type="string", example="Unauthorized")
 *      )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
