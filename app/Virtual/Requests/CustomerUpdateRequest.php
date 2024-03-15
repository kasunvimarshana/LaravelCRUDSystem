<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Customer Update request",
 *      description="Customer Update request body data",
 *      type="object",
 * )
 */
 class CustomerUpdateRequest
 {
     /**
      * @OA\Property(
      *      title="name",
      *      description="Customer Name",
      *      example="Kasun Vimarshana"
      * )
      *
      * @var string
      */
     public $name;

     /**
      * @OA\Property(
      *      title="address",
      *      description="Customer Address",
      *      example="Address"
      * )
      *
      * @var string
      */
     public $address;

     /**
      * @OA\Property(
      *      title="phone",
      *      description="Customer Name",
      *      example="0000000000",
      * )
      *
      * @var string
      */
     public $phone;

     /**
      * @OA\Property(
      *      title="email",
      *      description="Customer Email",
      *      example="kasunvmail@gmail.com",
      * )
      *
      * @var string
      */
      public $email;
 }

