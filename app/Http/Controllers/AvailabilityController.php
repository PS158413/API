<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvailabilityRequest;
use App\Repositories\AvailabilityRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AvailabilityController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Category Repository class.
     *
     * @var AvailabilityRepository
     */
    public $AvailabilityRepository;

    public function __construct(AvailabilityRepository $AvailabilityRepository)
    {
        $this->middleware('auth:api', ['except' => ['']]);
        $this->AvailabilityRepository = $AvailabilityRepository;
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/availability",
     *     tags={"Availability"},
     *     summary="Get Availability List",
     *     description="Get availability List as Array",
     *     operationId="index_availability",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200,description="Get availability List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index_availability(): JsonResponse
    {
        try {
            $data = $this->AvailabilityRepository->getAll();

            return $this->responseSuccess($data, 'Availability List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/availability/all",
     *     tags={"Availability"},
     *     summary="All Availability - Accessible",
     *     description="All Availability - Accessible",
     *     operationId="availabilityAll",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="perPage", description="perPage, eg; 10", example=10, in="query", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="All Availability - Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function availabilityAll(Request $request): JsonResponse
    {
        try {
            $data = $this->AvailabilityRepository->getPaginatedData($request->perPage);

            return $this->responseSuccess($data, 'Availability List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/availability",
     *     tags={"Availability"},
     *     summary="Create New availability",
     *     description="Create New availability",
     *     operationId="store_availability",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="absence", type="string", example="Sick"),
     *              @OA\Property(property="start_time", type="datetime", example="06-06-2023 07:37"),
     *              @OA\Property(property="finish_time", type="datetime", example="07-06-2023 10:37"),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *
     *      @OA\Response(response=200, description="Create New Availability" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store_availability(AvailabilityRequest $request): JsonResponse
    {
        try {
            $availability = $this->AvailabilityRepository->create($request->all());

            return $this->responseSuccess($availability, 'New availability Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/availability/{id}",
     *     tags={"Availability"},
     *     summary="Show availability info",
     *     description="Show availability info",
     *     operationId="show_availability",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Show availability info"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show_availability($id): JsonResponse
    {
        try {
            $data = $this->AvailabilityRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Availability Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Availability Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api_gv/public/api/availability/{id}",
     *     tags={"Availability"},
     *     summary="Delete Availability",
     *     description="Delete Availability",
     *     operationId="destroy_availability",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Delete Availability"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy_availability($id): JsonResponse
    {
        try {
            $availability = $this->AvailabilityRepository->getByID($id);
            if (empty($availability)) {
                return $this->responseError(null, 'Availability Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->AvailabilityRepository->delete($id);
            if (! $deleted) {
                return $this->responseError(null, 'Failed to delete the Availability.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($availability, 'Availability Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api_gv/public/api/availability/{id}",
     *     tags={"Availability"},
     *     summary="Update availability",
     *     description="Update availability",
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="absence", type="string", example="Sick"),
     *              @OA\Property(property="start_time", type="datetime", example="06-07-2023 07:37"),
     *              @OA\Property(property="finish_time", type="datetime", example="07-08-2023 10:37"),
     *          ),
     *      ),
     *     operationId="update_availability",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Update Availability"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update_availability(AvailabilityRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->AvailabilityRepository->update($id, $request->all());
            if (is_null($data)) {
                return $this->responseError(null, 'Availability Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Availability Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
