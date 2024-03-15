<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Exception;
use App\Traits\ResponseTrait;
use App\Repositories\AuthRepository;

class ProfileController extends Controller
{
    use ResponseTrait;

    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"Authentication"},
     *     summary="User profile",
     *     description="User profile",
     *     operationId="profile_show",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthennticated"
     *     ),
     * )
     */
    public function show(): JsonResponse
    {
        Log::debug("ProfileController->show");
        try {
            abort_if(Gate::denies('read-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            return $this->responseSuccess(Auth::guard()->user(), 'User profile data !');
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *     description="User logout",
     *     operationId="logout",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        Log::debug("ProfileController->logout");
        try {
            $data = $this->auth->logout();
            return $this->responseSuccess(null, 'User logged out successfully !');
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }

}


