<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Customer Medication Return Request",
 *      description="Customer Medication Return Request body data",
 *      type="object",
 *      required={"customer_id", "medication_id", "quantity"},
 * )
 */
 class CustomerMedicationReturnRequest
 {
    // /**
    // * @OA\Property(
    // *      title="transaction_type",
    // *      description="Transaction Type",
    // *      example="purchase|return"
    // * )
    // *
    // * @var string
    // */
    // public $transaction_type;

    /**
     * @OA\Property(
     *      title="quantity",
     *      description="Quantity",
     *      example=1
     * )
     *
     * @var numeric
     */
    public $quantity;

    /**
     * @OA\Property(
     *      title="remarks",
     *      description="additional details about the transaction",
     *      example="remark",
     * )
     *
     * @var string
     */
    public $remarks;

    /**
     * @OA\Property(
     *      title="medication_id",
     *      description="Medication id",
     *      example=1,
     * )
     *
     * @var numeric
     */
    public $medication_id;

    /**
     * @OA\Property(
     *      title="customer_id",
     *      description="Customer id",
     *      example=1,
     * )
     *
     * @var numeric
     */
    public $customer_id;
}
 
 