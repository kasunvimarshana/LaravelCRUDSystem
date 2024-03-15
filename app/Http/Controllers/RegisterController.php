<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Gate;
use Exception;
use App\Traits\ResponseTrait;
use App\Repositories\AuthRepository;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    use ResponseTrait;

    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     tags={"Authentication"},
     *     summary="Register",
     *     description="Register new User",
     *     operationId="register",
     *     @OA\RequestBody(
     *         description="Register new User",
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="Name",
     *                     type="string",
     *                     example="Kasun Vimarshana"
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     description="Username",
     *                     type="string",
     *                     example="kasun"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string",
     *                     example="password"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     description="User confirm password",
     *                     type="string",
     *                     example="password"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     description="User Role",
     *                     type="string",
     *                     example="owner|manager|cashier"
     *                 ),
     *                 required={"name", "username", "password", "password_confirmation", "role"}
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
    public function register(RegisterRequest $request): JsonResponse
    {
        Log::debug("RegisterController->register");
        try {
            // if ($request->user() && !$request->user()->can('create-records')) {
            //     abort(Response::HTTP_FORBIDDEN);
            // }
            $data = $this->auth->register($request->all());
            return $this->responseSuccess($data, 'User registered successfully.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
}
