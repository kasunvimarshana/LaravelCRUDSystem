<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Medication Create request",
 *      description="Medication Create request body data",
 *      type="object",
 *      required={"name"},
 * )
 */
 class MedicationCreateRequest
 {
     /**
      * @OA\Property(
      *      title="name",
      *      description="Medication Name",
      *      example="Medication 01"
      * )
      *
      * @var string
      */
     public $name;

     /**
      * @OA\Property(
      *      title="description",
      *      description="Medication Description",
      *      example="Description"
      * )
      *
      * @var string
      */
     public $description;

     /**
      * @OA\Property(
      *      title="quantity",
      *      description="Medication Quantity",
      *      example=1,
      * )
      *
      * @var numeric
      */
     public $quantity;

 }

