<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Product Repository class.
     *
     * @var ProductRepository
     */
    public $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->middleware('auth:api', ['except' => ['indexAll', 'show_product']]);
        $this->productRepository = $productRepository;
    }

        /**
         * @OA\GET(
         *     path="/api_gv/public/api/product",
         *     tags={"Product"},
         *     summary="Get Product List",
         *     description="Get Product List as Array",
         *     operationId="index_product",
         *     security={{"bearer":{}}},
         *
         *     @OA\Response(response=200,description="Get Product List as Array"),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function index_product(): JsonResponse
        {
            try {
                $data = $this->productRepository->getAll();

                return $this->responseSuccess($data, 'Product List Fetch Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\GET(
         *     path="/api_gv/public/api/product/all",
         *     tags={"Product"},
         *     summary="All Products - Publicly Accessible",
         *     description="All Products - Publicly Accessible",
         *     operationId="indexAll",
         *
         *     @OA\Parameter(name="perPage", description="perPage, eg; 10", example=10, in="query", @OA\Schema(type="integer")),
         *
         *     @OA\Response(response=200, description="All Products - Publicly Accessible" ),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function indexAll(Request $request): JsonResponse
        {
            try {
                $data = $this->productRepository->getPaginatedData($request->perPage);

                return $this->responseSuccess($data, 'Product List Fetched Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\POST(
         *     path="/api_gv/public/api/product",

         *     tags={"Product"},
         *     summary="Create New Product",
         *     description="Create New Product",
         *     operationId="store_product",
         *
         *     @OA\RequestBody(
         *
         *          @OA\JsonContent(
         *              type="object",

         *
         *              @OA\Property(property="article_number", type="integer", example="1"),
         *              @OA\Property(property="name", type="string", example="nickplant"),
         *              @OA\Property(property="description", type="string", example="Nick is een plant"),
         *              @OA\Property(property="price", type="integer", example="20"),
         *              @OA\Property(property="image", type="string", example="image/plant.jpg"),
         *              @OA\Property(property="color", type="string", example="Blue"),
         *              @OA\Property(property="height_cm", type="integer", example="178"),
         *              @OA\Property(property="width_cm", type="integer", example="100"),
         *              @OA\Property(property="depth_cm", type="integer", example="30"),
         *              @OA\Property(property="weight_gr", type="integer", example="20"),
         *              @OA\Property(property="barcode", type="integer", example="123456789"),
         *              @OA\Property(property="stock", type="integer", example="7"),
         *          ),
         *      ),
         *      security={{"bearer":{}}},
         *
         *      @OA\Response(response=200, description="Create New Product" ),
         *      @OA\Response(response=400, description="Bad request"),
         *      @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function store_product(ProductRequest $request): JsonResponse
        {
            try {
                $product = $this->productRepository->create($request->all());

                return $this->responseSuccess($product, 'New Product Created Successfully !');
            } catch (\Exception $exception) {
                return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\GET(
         *     path="/api_gv/public/api/product/{id}",
         *     tags={"Product"},
         *     summary="Show Product Details",
         *     description="Show Product Details",
         *     operationId="show_product",
         *     security={{"bearer":{}}},
         *
         *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
         *
         *     @OA\Response(response=200, description="Show Product Details"),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function show_product($id): JsonResponse
        {
            try {
                $data = $this->productRepository->getByID($id);
                if (is_null($data)) {
                    return $this->responseError(null, 'Product Not Found', Response::HTTP_NOT_FOUND);
                }

                return $this->responseSuccess($data, 'Product Details Fetch Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\PUT(
         *     path="/api_gv/public/api/product/{id}",
         *     tags={"Product"},
         *     summary="Update Product",
         *     description="Update Product",
         *
         *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
         *
         *     @OA\RequestBody(
         *
         *          @OA\JsonContent(
         *              type="object",
         *
         *              @OA\Property(property="article_number", type="integer", example="1"),
         *              @OA\Property(property="name", type="string", example="hasanplant"),
         *              @OA\Property(property="description", type="string", example="Hasan is een plant"),
         *              @OA\Property(property="price", type="integer", example="20"),
         *              @OA\Property(property="image", type="string", example="image/plant.jpg"),
         *              @OA\Property(property="color", type="string", example="Blue"),
         *              @OA\Property(property="height_cm", type="integer", example="178"),
         *              @OA\Property(property="width_cm", type="integer", example="100"),
         *              @OA\Property(property="depth_cm", type="integer", example="30"),
         *              @OA\Property(property="weight_gr", type="integer", example="20"),
         *              @OA\Property(property="barcode", type="integer", example="123456789"),
         *              @OA\Property(property="stock", type="integer", example="7"),
         *              @OA\Property(property="category", type="string", example="buiten planten"),
         *          ),
         *      ),
         *     operationId="update_product",
         *     security={{"bearer":{}}},
         *
         *     @OA\Response(response=200, description="Update Product"),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function update_product(ProductRequest $request, $id): JsonResponse
        {
            try {
                $data = $this->productRepository->update($id, $request->all());
                if (is_null($data)) {
                    return $this->responseError(null, 'Product Not Found', Response::HTTP_NOT_FOUND);
                }

                return $this->responseSuccess($data, 'Product Updated Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\DELETE(
         *     path="/api_gv/public/api/product/{id}",
         *     tags={"Product"},
         *     summary="Delete Product",
         *     description="Delete Product",
         *     operationId="destroy_product",
         *     security={{"bearer":{}}},
         *
         *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
         *
         *     @OA\Response(response=200, description="Delete Product"),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function destroy_product($id): JsonResponse
        {
            try {
                $product = $this->productRepository->getByID($id);
                if (empty($product)) {
                    return $this->responseError(null, 'Product Not Found', Response::HTTP_NOT_FOUND);
                }

                $deleted = $this->productRepository->delete($id);
                if (! $deleted) {
                    return $this->responseError(null, 'Failed to delete the product.', Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                return $this->responseSuccess($product, 'Product Deleted Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        public function sync($order_id)
        {
            try {
                //get id from request
                $user = Auth::user();
            //            dd($user->id);

                $token = env('Kuin_token');
                //find order on order_id
                $response = Http::withToken($token)->get('https://kuin.summaict.nl/api/orderItem?order_id='.$order_id);
                $orderItems = $response->json();
                foreach ($orderItems as $orderItem) {
                    $productId = $orderItem['product_id'];
                    $quantity = $orderItem['quantity'];
                    $productResponse = Http::withToken($token)->get('https://kuin.summaict.nl/api/product/'.$productId);
                    $productDetails = $productResponse->json();
                    $product = Product::where('article_number', $productId)->first();
                    if (! $product) {
                        // Update the existing product in the database with the new details
                        $product = new Product($productDetails);
                        $product->user_id = $user->id;
                        $product->article_number = $productId;
                        $product->barcode = rand(100000000, 999999999);
                        $product->stock = $quantity;
                        $product->save();
                    } else {
                        $product->fill($productDetails);
                        $product->stock += $quantity;
                        $product->save();
                        // Create a new product in the database with the ordered product details
                    }
                }

                //return with success message
                return $orderItems;
            } catch (\Exception $e) {
                // Handle the exception
                // Log the error or display a friendly message to the user
                dd($e->getMessage());

                return response()->json();
            }
        }
}
