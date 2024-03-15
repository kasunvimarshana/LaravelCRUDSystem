<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *     title="JsonResponseResource",
 *     description="Json Response Resource",
 *     @OA\Xml(
 *         name="JsonResponseResource"
 *     )
 * )
 */
class JsonResponseResource
{
    /**
     * @OA\Property(
     *     title="Status",
     *     description="Status"
     * )
     *
     * @var string
     */
    private $status;

    /**
     * @OA\Property(
     *     title="Message",
     *     description="Message"
     * )
     *
     * @var string
     */
    private $message;

    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data"
     * )
     *
     * @var array|object|null
     */
    private $data;

    /**
     * @OA\Property(
     *     title="Errors",
     *     description="Errors"
     * )
     *
     * @var string|array|object|null
     */
    private $errors;

}
