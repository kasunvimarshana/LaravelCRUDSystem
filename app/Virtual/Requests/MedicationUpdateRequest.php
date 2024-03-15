<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Medication Update request",
 *      description="Medication Update request body data",
 *      type="object",
 * )
 */
 class MedicationUpdateRequest
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

