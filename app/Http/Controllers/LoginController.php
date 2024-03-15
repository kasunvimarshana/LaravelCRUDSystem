<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Exception;
use App\Traits\ResponseTrait;
use App\Repositories\AuthRepository;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    use ResponseTrait;

    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Login to system",
     *     operationId="login",
     *     @OA\RequestBody(
     *         description="Login to system",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="username",
     *                     description="Username",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                 required={"username", "password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        Log::debug("LoginController->login");
        try {
            $data = $this->auth->login($request->all());
            return $this->responseSuccess($data, 'Logged in successfully.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
}
