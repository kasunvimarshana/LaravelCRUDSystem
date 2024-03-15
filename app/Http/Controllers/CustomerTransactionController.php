<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use DB;
use Exception;
use App\Traits\ResponseTrait;
use App\Repositories\CustomerTransactionRepository;
use App\Http\Requests\CustomerMedicationPurchaseRequest;
use App\Http\Requests\CustomerMedicationReturnRequest;
use App\Http\Requests\CustomerTransactionQueryRequest;
use App\Http\Resources\PaginateResource;

class CustomerTransactionController extends Controller
{
    //
    use ResponseTrait;

    public function __construct(private CustomerTransactionRepository $customerTransaction)
    {
        $this->customerTransaction = $customerTransaction;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/customer-medication-purchase/store",
     *     tags={"CustomerTransactions"},
     *     summary="Store new Customer Medication Purchas",
     *     description="Store new Customer Medication Purchas",
     *     operationId="purchaseMedication",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="Register new Customer Medication Purchas",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CustomerMedicationPurchaseRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function purchaseMedication(CustomerMedicationPurchaseRequest $request): JsonResponse
    {
        //
        Log::debug("CustomerTransactionController->purchaseMedication");
        try {
            DB::beginTransaction();
            abort_if(Gate::denies('create-customer-purchase-medication'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $data = $this->customerTransaction->purchaseMedication($request->all());
            DB::commit();
            return $this->responseSuccess($data, 'Customer Transaction created successfully.', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollback(); 
            return $this->responseError([], $exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/customer-medication-return/store",
     *     tags={"CustomerTransactions"},
     *     summary="Store new Customer Medication Return",
     *     description="Store new Customer Medication Return",
     *     operationId="returnMedication",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="Create new Customer Medication Return",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CustomerMedicationReturnRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function returnMedication(CustomerMedicationReturnRequest $request): JsonResponse
    {
        //
        Log::debug("CustomerTransactionController->returnMedication");
        try {
            DB::beginTransaction();
            abort_if(Gate::denies('create-customer-return-medication'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $data = $this->customerTransaction->returnMedication($request->all());
            DB::commit();
            return $this->responseSuccess($data, 'Customer Transaction created successfully.', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollback(); 
            return $this->responseError([], $exception->getMessage());
        }
    }
}
