<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Repositories\CheckoutRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class CheckoutController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * product Repository class.
     *
     * @var CheckoutRepository
     */
    public $CheckoutRepository;

    public function __construct(CheckoutRepository $CheckoutRepository)
    {
        $this->middleware('auth:api', ['except' => ['checkoutProduct_All', 'post_product_by_barcode', 'post_receipt', 'post_checkoutproduct', 'get_all_receipts', 'delete_checkoutproduct_by_id', 'getUserByCustomernumber']]);
        $this->CheckoutRepository = $CheckoutRepository;
    }

    // get list of all checkoutproducts

    /**
     * @OA\GET(
     * path="/api/product/checkoutproduct/all",
     * tags={"Checkout"},
     * summary="Show list of all checkoutproducts",
     * description="Show list of all checkoutproducts",
     * operationId="checkoutProduct_All",
     *
     * @OA\Response(response=200, description="Show list of all checkoutproducts"),
     * @OA\Response(response=400, description="Bad request"),
     * @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function checkoutProduct_All(): JsonResponse
    {
        try {
            $data = $this->CheckoutRepository->getAllCheckoutProducts();
            if (is_null($data)) {
                return $this->responseError(null, 'checkoutproducts Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'checkoutproducts Info Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // get product by code
    /**
     * @OA\GET(
     *     path="/api_gv/public/api/product/code/{code}",
     *     tags={"Checkout"},
     *     summary="Show product info by code",
     *     description="Show product info by code",
     *     operationId="show_product_by_code",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="code", description="code, eg; 123456789", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Show product info by code"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show_product_by_code($productCode): JsonResponse
    {
        try {
            $data = $this->CheckoutRepository->getProductByCode($productCode);
            if (is_null($data)) {
                return $this->responseError(null, 'product Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'product Info Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // post checkoutproduct to table
    /**
     * @OA\POST(
     *     path="/api_gv/public/api/product/checkoutproduct",
     *     tags={"Checkout"},
     *     summary="Post product to table",
     *     description="Post product to table",
     *     operationId="post_product",
     *     security={{"bearer":{}}},
     *
     *     @OA\RequestBody(
     *         description="Post product to table",
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"article_number","name","price","user_id"},
     *
     *             @OA\Property(property="article_number", type="integer", example="123456789"),
     *             @OA\Property(property="name", type="string", example="product name"),
     *             @OA\Property(property="price", type="integer", example="123456789"),
     *            @OA\Property(property="quantity", type="integer", example="1"),
     *             @OA\Property(property="user_id", type="integer", example="123456789"),
     *         ),
     *     ),
     *
     *     @OA\Response(response=200, description="Post product to table"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function post_checkoutproduct(CheckoutRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $checkoutproduct = $this->CheckoutRepository->postCheckoutProduct($input);

            return $this->responseSuccess($checkoutproduct, 'New checkoutproduct Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // post product to other table by searching barcode
    /**
     * @OA\POST(
     *     path="/api_gv/public/api/product/barcode/{barcode}",
     *     tags={"Checkout"},
     *     summary="Post product to other table by searching barcode",
     *     description="Post product to other table by searching barcode",
     *     operationId="post_product_by_barcode",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="barcode", description="barcode, eg; 123456789", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Post product to other table by searching barcode"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function post_product_by_barcode($barcode): JsonResponse
    {
        try {
            $data = $this->CheckoutRepository->postproductByBarcode($barcode);
            if (is_null($data)) {
                return $this->responseError(null, 'product Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'product Info Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // delete checkoutproduct by id

    /**
     * @OA\DELETE(
     *     path="/api_gv/public/api/product/checkoutproduct/{id}",
     *     tags={"Checkout"},
     *     summary="Delete checkoutproduct by id",
     *     description="Delete checkoutproduct by id",
     *     operationId="delete_checkoutproduct_by_id",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Delete checkoutproduct by id"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function delete_checkoutproduct_by_id($id): JsonResponse
    {
        try {
            $data = $this->CheckoutRepository->deleteCheckoutProductById($id);
            if (is_null($data)) {
                return $this->responseError(null, 'checkoutproduct Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'checkoutproduct Info Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //  make function to delete all checkoutproducts
    /**
     * @OA\DELETE(
     *     path="/api_gv/public/api/product/checkoutproduct",
     *     tags={"Checkout"},
     *     summary="Delete all checkoutproducts",
     *     description="Delete all checkoutproducts",
     *     operationId="delete_all_checkoutproducts",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Delete all checkoutproducts"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function delete_all_checkoutproducts(): JsonResponse
    {
        try {
            $data = $this->CheckoutRepository->deleteAllCheckoutProducts();
            if (is_null($data)) {
                return $this->responseError(null, 'checkoutproducts Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'checkoutproducts Info Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Hieronder vindt u een voorbeeld van een functie die alle gegevens uit de 'receipt_product' tabel haalt en deze overzet naar de 'receipt' tabel in Laravel.
    /**
     * @OA\POST(
     *     path="/api_gv/public/api/product/receipt",
     *     tags={"Checkout"},
     *     summary="receipt_product to receipt",
     *     description="receipt_product to receipt",
     *     operationId="post_receipt",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="receipt_product to receipt"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function post_receipt(Request $request): JsonResponse
    {
        try {
            $input = $request->all();
            $receipt = $this->CheckoutRepository->postReceipt($input);

            return $this->responseSuccess($receipt, 'New receipt Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // get all receipts

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/receipts",
     *     tags={"Checkout"},
     *     summary="Get all receipts",
     *     description="Get all receipts",
     *     operationId="get_all_receipts",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Get all receipts"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function get_all_receipts(): JsonResponse
    {
        try {
            $data = $this->CheckoutRepository->getAllReceipts();
            if (is_null($data)) {
                return $this->responseError(null, 'receipts Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'receipts Info Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // login with customernumber

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/login",
     *     tags={"Checkout"},
     *     summary="Login with customernumber",
     *     description="Login with customernumber",
     *     operationId="login_with_customernumber",
     *
     *     @OA\RequestBody(
     *         description="Login with customernumber",
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"customernumber","password"},
     *
     *             @OA\Property(property="customernumber", type="integer", example="123456789"),
     *             @OA\Property(property="password", type="string", example="password"),
     *         ),
     *     ),
     *
     *     @OA\Response(response=200, description="Login with customernumber"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function login_with_customernumber(Request $request): JsonResponse
    {
        try {
            $input = $request->all();
            $user = $this->CheckoutRepository->loginWithCustomernumber($input);

            return $this->responseSuccess($user, 'User Logged In Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // get user by customernumber

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/user/customernumber/{customernumber}",
     *     tags={"Checkout"},
     *     summary="Get user by customernumber",
     *     description="Get user by customernumber",
     *     operationId="user_by_customernumber",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="customernumber", description="customernumber, eg; 123456789", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Get user by customernumber"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function getUserByCustomernumber($customernumber): JsonResponse
    {
        try {
            $data = $this->CheckoutRepository->getUserByCustomernumber($customernumber);
            if (is_null($data)) {
                return $this->responseError(null, 'user Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'user Info Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // get all customers

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/user/customernumber/all",
     *     tags={"Checkout"},
     *     summary="Get all customers",
     *     description="Get all customers",
     *     operationId="getAllCustomers",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Get all customers"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function getAllCustomers(): JsonResponse
    {
        try {
            $data = $this->CheckoutRepository->getAllCustomers();
            if (is_null($data)) {
                return $this->responseError(null, 'customers Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'customers Info Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



}
