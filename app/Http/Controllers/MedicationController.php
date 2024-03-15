<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Exception;
use App\Traits\ResponseTrait;
use App\Repositories\MedicationRepository;
use App\Http\Requests\MedicationCreateRequest;
use App\Http\Requests\MedicationUpdateRequest;
use App\Http\Requests\MedicationQueryRequest;
use App\Http\Resources\PaginateResource;

class MedicationController extends Controller
{
    use ResponseTrait;

    public function __construct(private MedicationRepository $medication)
    {
        $this->medication = $medication;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/medications/store",
     *     tags={"Medications"},
     *     summary="Store new Medication",
     *     description="Store new Medication",
     *     operationId="medication_store",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="new Medication",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/MedicationCreateRequest")
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
    public function store(MedicationCreateRequest $request): JsonResponse
    {
        //
        Log::debug("MedicationController->store");
        try {
            abort_if(Gate::denies('create-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $data = $this->medication->create($request->all());
            return $this->responseSuccess($data, 'Medication created successfully.', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/medications/show/{id}",
     *     tags={"Medications"},
     *     summary="Select existing Medication",
     *     description="Select existing Medication",
     *     operationId="medication_show",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Medication id",
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
        Log::debug("MedicationController->show");
        try {
            abort_if(Gate::denies('read-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            return $this->responseSuccess($this->medication->getById($id), 'Medication Data', Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/medications/filter",
     *     tags={"Medications"},
     *     summary="Filter existing Medications",
     *     description="Filter existing Medications",
     *     operationId="medication_filter",
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
    public function filter(MedicationQueryRequest $request): JsonResponse
    {
        Log::debug("MedicationController->filter");
        try {
            abort_if(Gate::denies('read-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            return $this->responseSuccess(new PaginateResource($this->medication->getAll($request->all())), 'Medication Data', Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/medications/update/{id}",
     *     tags={"Medications"},
     *     summary="Update existing Medication",
     *     description="Update existing Medication",
     *     operationId="medication_update",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Medication id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update existing Medication",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/MedicationUpdateRequest")
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
    public function update(MedicationUpdateRequest $request, $id)
    {
        Log::debug("MedicationController->update");
        try {
            abort_if(Gate::denies('update-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $data = $this->medication->update($id, $request->all());
            return $this->responseSuccess($data, 'Medication updated successfully.', Response::HTTP_ACCEPTED);
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/medications/flag-as-deleted/{id}",
     *      tags={"Medications"},
     *      summary="Soft Delete existing Medication",
     *      description="Soft Delete existing Medication",
     *      operationId="medication_flagAsDeleted",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Medication id",
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
        Log::debug("MedicationController->flagAsDeleted");
        try {
            abort_if(Gate::denies('soft-delete-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $this->medication->delete($id);
            return $this->responseSuccess(null, 'Deleted', Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/medications/permanently-delete-records/{id}",
     *      tags={"Medications"},
     *      summary="Force Delete existing Medication",
     *      description="Force Delete existing Medication",
     *      operationId="medication_destroyPermanently",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Medication id",
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
        Log::debug("MedicationController->destroyPermanently");
        try {
            abort_if(Gate::denies('permanently-delete-records'), Response::HTTP_FORBIDDEN, 'Forbidden');
            $this->medication->forceDelete($id);
            return $this->responseSuccess(null, 'Deleted', Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->responseError(null, $exception->getMessage());
        }
    }
}

