<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version=L5_SWAGGER_CONST_VERSION,
 *     title=L5_SWAGGER_CONST_TITLE,
 *     description=L5_SWAGGER_CONST_DESCRIPTION,
 *     @OA\Contact(
 *         name=L5_SWAGGER_CONST_CONTACT_NAME,
 *         email=L5_SWAGGER_CONST_CONTACT_EMAIL
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * ),
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local server"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearer_token",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Please Login to get the authentication token",
 *     name="Token based Based",
 * ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;
}

