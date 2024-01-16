<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Repositories\CategoryRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Category Repository class.
     *
     * @var CategoryRepository
     */
    public $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->middleware('auth:api', ['except' => ['categoryAll', 'show_category']]);
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/category",
     *     tags={"Category"},
     *     summary="Get Category List",
     *     description="Get category List as Array",
     *     operationId="index_category",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200,description="Get category List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index_category(): JsonResponse
    {
        try {
            $data = $this->categoryRepository->getAll();

            return $this->responseSuccess($data, 'Category List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/category/all",
     *     tags={"Category"},
     *     summary="All Category - Publicly Accessible",
     *     description="All Categories - Publicly Accessible",
     *     operationId="categoryAll",
     *
     *     @OA\Response(response=200, description="All Categories - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function categoryAll(Request $request): JsonResponse
    {
        try {
            $data = $this->categoryRepository->getPaginatedData($request->perPage);

            return $this->responseSuccess($data, 'Category List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/category",
     *     tags={"Category"},
     *     summary="Create New category",
     *     description="Create New category",
     *     operationId="store_category",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="category", type="string", example="buiten planten"),
     *              @OA\Property(property="name", type="string", example="nickplant"),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *
     *      @OA\Response(response=200, description="Create New Category" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store_category(CategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryRepository->create($request->all());

            return $this->responseSuccess($category, 'New Category Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/category/{id}",
     *     tags={"Category"},
     *     summary="Show category info",
     *     description="Show category info",
     *     operationId="show_category",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Show category info"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show_category($id): JsonResponse
    {
        try {
            $data = $this->categoryRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Category Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Category Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api_gv/public/api/category/{id}",
     *     tags={"Category"},
     *     summary="Update Category",
     *     description="Update Category",
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="category", type="string", example="Bloemen"),
     *          ),
     *      ),
     *     operationId="update_category",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Update Category"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update_category(CategoryRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->categoryRepository->update($id, $request->all());
            if (is_null($data)) {
                return $this->responseError(null, 'Category Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Category Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api_gv/public/api/category/{id}",
     *     tags={"Category"},
     *     summary="Delete category",
     *     description="Delete category",
     *     operationId="destroy_category",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Delete category"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy_category($id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->getByID($id);
            if (empty($category)) {
                return $this->responseError(null, 'Category Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->categoryRepository->delete($id);
            if (! $deleted) {
                return $this->responseError(null, 'Failed to delete the Category.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($category, 'Category Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api_gv/public/api/category/detach/{id}",
     *     tags={"Category"},
     *     summary="Detach Product",
     *     description="Detach product",
     *     operationId="detach_product",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="name", type="string", example="nickplant"),
     *          ),
     *      ),
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Detach Product"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function detach_product($id, Request $request): JsonResponse
    {
        try {
            $category = $this->categoryRepository->getByID($id);
            if (empty($category)) {
                return $this->responseError(null, 'Category Not Found', Response::HTTP_NOT_FOUND);
            }

            $data = $request->all();

            $deleted = $this->categoryRepository->detach($id, $data);
            if (! $deleted) {
                return $this->responseError(null, 'Failed to detach the Category.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($category, 'Product detach Successfully whit Category  !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
