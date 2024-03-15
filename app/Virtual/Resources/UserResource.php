<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *     title="UserResource",
 *     description="User Resource",
 *     @OA\Xml(
 *         name="UserResource"
 *     )
 * )
 */
class UserResource
{
    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Virtual\Models\User
     */
    private $data;
}
