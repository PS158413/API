<?php

namespace App\Http\Controllers\Kuin;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProductsController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/kuin/products",
     *     tags={"Product Kuin"},
     *     summary="Get product List",
     *     description="Get product List as Array",
     *     operationId="index_kuin",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200,description="Get product List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index_kuin()
    {
        try {
            // Validate the token
            $tokenKuin = env('Kuin_token');
            $token = JWTAuth::getToken();
            if (! $token || ! JWTAuth::check($token)) {
                return $this->responseError(null, 'Invalid token', Response::HTTP_UNAUTHORIZED);
            }
            // Token is valid, proceed with the request
            $response = Http::withOptions(['verify' => false])->withToken($tokenKuin)->get('https://kuin.summaict.nl/api/product');
            $products = $response->json();

            return $this->responseSuccess($products, 'Product List Fetch Successfully!');
        } catch (JWTException $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
