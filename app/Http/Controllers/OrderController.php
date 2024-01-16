<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Category Repository class.
     *
     * @var OrderRepository
     */
    public $OrderRepository;

    public function __construct(OrderRepository $OrderRepository)
    {
        $this->middleware('auth:api', ['except' => ['']]);
        $this->OrderRepository = $OrderRepository;
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/order",
     *     tags={"Order"},
     *     summary="Get Order List",
     *     description="Get order List as Array",
     *     operationId="index_order",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200,description="Get order List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index_order(): JsonResponse
    {
        try {
            $data = $this->OrderRepository->getAll();

            return $this->responseSuccess($data, 'Order List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/order/all",
     *     tags={"Order"},
     *     summary="All Order - Publicly Accessible",
     *     description="All Orders - Publicly Accessible",
     *     operationId="OrderAll",
     *
     *     @OA\Parameter(name="perPage", description="perPage, eg; 10", example=10, in="query", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="All Orders - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function OrderAll(Request $request): JsonResponse
    {
        try {
            $data = $this->OrderRepository->getPaginatedData($request->perPage);

            return $this->responseSuccess($data, 'Order List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/order",
     *     tags={"Order"},
     *     summary="Create New Order",
     *     description="Create New Order",
     *     operationId="store_order",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="quantity", type="integer", example="4"),
     *              @OA\Property(property="name", type="string", example="Product 50"),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *
     *      @OA\Response(response=200, description="Create New Category" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store_order(OrderRequest $request): JsonResponse
    {
        try {
            $order = $this->OrderRepository->create($request->all());

            return $this->responseSuccess($order, 'New Order Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/order/{id}",
     *     tags={"Order"},
     *     summary="Show Order info",
     *     description="Show Order info",
     *     operationId="show_order",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Show Order info"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show_order($id): JsonResponse
    {
        try {
            $data = $this->OrderRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Order Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Order Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
