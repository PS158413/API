<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Repositories\CustomerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\ResponseTrait;

class CustomerController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Customer Repository class.
     *
     * @var CustomerRepository
     */
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/customer",
     *     tags={"Customer Order"},
     *     summary="Customer Order",
     *     description="Customer Order",
     *     operationId="store_customer_order",
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="name", type="string", example="Hasan"),
     *             @OA\Property(property="phone", type="string", example="0681302145"),
     *             @OA\Property(property="email", type="string", example="hasan@gmail.com"),
     *             @OA\Property(property="zipcode", type="string", example="5737GB"),
     *             @OA\Property(property="huisnummer", type="string", example="71"),
     *             @OA\Property(property="address", type="string", example="Dorpsstraat 71, 5737GB Lieshout"),
     *             @OA\Property(property="city", type="string", example="Lieshout"),
     *             @OA\Property(property="total", type="integer", example="200"),
     *
     *
     *
     *
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="name", type="string", example="Product 50"),
     *                     @OA\Property(property="quantity", type="integer", example="4")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Customer Order Created"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function store_customer_order(CustomerRequest $request): JsonResponse
    {
        try {
            $order = $this->customerRepository->create($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $order,
                'message' => 'New Order Created Successfully!',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
