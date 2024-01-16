<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth:api', ['except' => ['']]);
        $this->userRepository = $userRepository;
    }

        /**
         * @OA\GET(
         *     path="/api_gv/public/api/user/all",
         *     tags={"Users"},
         *     summary="All Users - Publicly Accessible",
         *     description="All Users - Publicly Accessible",
         *     operationId="UserAll",
         *     security={{"bearer":{}}},
         *
         *     @OA\Parameter(name="perPage", description="perPage, eg; 10", example=10, in="query", @OA\Schema(type="integer")),
         *
         *     @OA\Response(response=200, description="All Users - Publicly Accessible" ),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function UserAll(Request $request): JsonResponse
        {
            try {
                $data = $this->userRepository->getPaginatedData($request->perPage);

                return $this->responseSuccess($data, 'Users List Fetched Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\GET(
         *     path="/api_gv/public/api/user",
         *     tags={"Users"},
         *     summary="Get User List",
         *     description="Get User List as Array",
         *     operationId="index",
         *     security={{"bearer":{}}},
         *
         *     @OA\Response(response=200,description="Get User List as Array"),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=401, description="Unauthorized"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function index(): JsonResponse
        {
            try {
                $data = $this->userRepository->getAll();

                return $this->responseSuccess($data, 'user List Fetch Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\GET(
         *     path="/api_gv/public/api/user/{id}",
         *     tags={"Users"},
         *     summary="Show user info",
         *     description="Show user info",
         *     operationId="show",
         *     security={{"bearer":{}}},
         *
         *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
         *
         *     @OA\Response(response=200, description="Show user info"),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function show($id): JsonResponse
        {
            try {
                $data = $this->userRepository->getByID($id);
                if (is_null($data)) {
                    return $this->responseError(null, 'Product Not Found', Response::HTTP_NOT_FOUND);
                }

                return $this->responseSuccess($data, 'Product Details Fetch Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\POST(
         *     path="/api_gv/public/api/user",
         *     tags={"Users"},
         *     summary="Create User",
         *     description="Create New User",
         *     operationId="store",
         *     security={{"bearer":{}}},
         *
         *     @OA\RequestBody(
         *
         *          @OA\JsonContent(
         *              type="object",
         *
         *        @OA\Property(property="name", type="string", example="Hasan"),
         *              @OA\Property(property="last_name", type="string", example="Hussein"),
         *              @OA\Property(property="city", type="string", example="Eindhoven"),
         *              @OA\Property(property="phone", type="integer", example="000000"),
         *              @OA\Property(property="email", type="string", example="hasan@gmail.com"),
         *              @OA\Property(property="birthday", type="date", example="13-04-2000"),
         *              @OA\Property(property="password", type="string", example="password"),
         *              @OA\Property(property="password_confirmation", type="string", example="password"),
         *              @OA\Property(property="role", type="string", example="staff"),
         *          ),
         *      ),
         *
         *      @OA\Response(response=200, description="Register New User Data" ),
         *      @OA\Response(response=400, description="Bad request"),
         *      @OA\Response(response=404, description="Resource Not Found")
         * )
         */
        public function store(UserRequest $request): JsonResponse
        {
            try {
                $user = $this->userRepository->create($request->all());

                return $this->responseSuccess($user, 'New Staff Created Successfully !');
            } catch (\Exception $exception) {
                return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\PUT(
         *     path="/api_gv/public/api/user/{id}",
         *     tags={"Users"},
         *     summary="Update user",
         *     description="Update user",
         *
         *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
         *
         *     @OA\RequestBody(
         *
         *          @OA\JsonContent(
         *              type="object",
         *
         *              @OA\Property(property="name", type="string", example="name"),
         *              @OA\Property(property="last_name", type="string", example="lastname"),
         *              @OA\Property(property="city", type="string", example="city"),
         *              @OA\Property(property="phone", type="integer", example="000000"),
         *              @OA\Property(property="email", type="string", example="manager@groenevingers.com"),
         *              @OA\Property(property="birthday", type="date", example="17-08-1990"),
         *              @OA\Property(property="password", type="string", example="password"),
         *              @OA\Property(property="password_confirmation", type="string", example="password"),
         *              @OA\Property(property="role", type="string", example="manager")
         *          ),
         *      ),
         *     operationId="update",
         *     security={{"bearer":{}}},
         *
         *     @OA\Response(response=200, description="Update user"),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function update(UserRequest $request, $id): JsonResponse
        {
            try {
                $requestData = $request->all();

                // Hash the password if it is provided
                if (isset($requestData['password'])) {
                    $requestData['password'] = Hash::make($requestData['password']);
                }

                $data = $this->userRepository->update($id, $requestData);
                if (is_null($data)) {
                    return $this->responseError(null, 'user Not Found', Response::HTTP_NOT_FOUND);
                }

                return $this->responseSuccess($data, 'user Updated Successfully!');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * @OA\DELETE(
         *     path="/api_gv/public/api/user/{id}",
         *     tags={"Users"},
         *     summary="Delete user member",
         *     description="Delete user member",
         *     operationId="destroy",
         *     security={{"bearer":{}}},
         *
         *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
         *
         *     @OA\Response(response=200, description="Delete user member"),
         *     @OA\Response(response=400, description="Bad request"),
         *     @OA\Response(response=404, description="Resource Not Found"),
         * )
         */
        public function destroy($id): JsonResponse
        {
            try {
                $product = $this->userRepository->getByID($id);
                if (empty($product)) {
                    return $this->responseError(null, 'user Not Found', Response::HTTP_NOT_FOUND);
                }

                $deleted = $this->userRepository->delete($id);
                if (! $deleted) {
                    return $this->responseError(null, 'Failed to delete the user.', Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                return $this->responseSuccess($product, 'user Deleted Successfully !');
            } catch (\Exception $e) {
                return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
}
