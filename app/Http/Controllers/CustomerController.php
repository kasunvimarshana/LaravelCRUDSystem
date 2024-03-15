<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Exception;
use App\Traits\ResponseTrait;
use App\Repositories\CustomerRepository;
use App\Http\Requests\CustomerCreateRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\CustomerQueryRequest;
use App\Http\Resources\PaginateResource;

class CustomerController extends Controller
{
    use ResponseTrait;

    public function __construct(private CustomerRepository $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/customers/store",
     *     tags={"Customers"},
     *     summary="Store new Customer",
     *     description="Store new Customer",
     *     operationId="customer_store",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="Register new Customer",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CustomerCreateRequest")
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
    public function store(CustomerCreateRequest $request): JsonResponse
    {
        //
        Log::debug("CustomerController->store");
        try {
            abort_if(Gate::denies('create-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $data = $this->customer->create($request->all());
            return $this->responseSuccess($data, 'Customer created successfully.', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/customers/show/{id}",
     *     tags={"Customers"},
     *     summary="Select existing Customer",
     *     description="Select existing Customer",
     *     operationId="customer_show",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Customer id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthennticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource Not Found"
     *     ),
     * )
     */
    public function show($id): JsonResponse
    {
        Log::debug("CustomerController->show");
        try {
            abort_if(Gate::denies('read-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            return $this->responseSuccess($this->customer->getById($id), 'Customer Data', Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/customers/filter",
     *     tags={"Customers"},
     *     summary="Filter existing Customers",
     *     description="Filter existing Customers",
     *     operationId="customer_filter",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="perPage",
     *         description="Page Limit",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example=10
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         description="Page",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example=0
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         description="Search term",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthennticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     * )
     */
    public function filter(CustomerQueryRequest $request): JsonResponse
    {
        Log::debug("CustomerController->filter");
        try {
            abort_if(Gate::denies('read-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            return $this->responseSuccess(new PaginateResource($this->customer->getAll($request->all())), 'Customer Data', Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/customers/update/{id}",
     *     tags={"Customers"},
     *     summary="Update existing Customer",
     *     description="Update existing Customer",
     *     operationId="customer_update",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Customer id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update existing Customer",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CustomerUpdateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/JsonResponseResource"),
     *     )
     * )
     */
    public function update(CustomerUpdateRequest $request, $id)
    {
        Log::debug("CustomerController->update");
        try {
            abort_if(Gate::denies('update-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $data = $this->customer->update($id, $request->all());
            return $this->responseSuccess($data, 'Customer updated successfully.', Response::HTTP_ACCEPTED);
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/customers/flag-as-deleted/{id}",
     *      tags={"Customers"},
     *      summary="Soft Delete existing Customer",
     *      description="Soft Delete existing Customer",
     *      operationId="customer_flagAsDeleted",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Customer id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function flagAsDeleted($id): JsonResponse
    {
        Log::debug("CustomerController->flagAsDeleted");
        try {
            abort_if(Gate::denies('soft-delete-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $this->customer->delete($id);
            return $this->responseSuccess(null, 'Deleted', Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/customers/permanently-delete-records/{id}",
     *      tags={"Customers"},
     *      summary="Force Delete existing Customer",
     *      description="Force Delete existing Customer",
     *      operationId="customer_destroyPermanently",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Customer id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroyPermanently($id): JsonResponse
    {
        Log::debug("CustomerController->destroyPermanently");
        try {
            abort_if(Gate::denies('permanently-delete-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $this->customer->forceDelete($id);
            return $this->responseSuccess(null, 'Deleted', Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }
}

