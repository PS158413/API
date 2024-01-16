<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Repositories\RoleRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Product Repository class.
     *
     * @var RoleRepository
     */
    public $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->middleware('auth:api', ['except' => ['']]);
        $this->roleRepository = $roleRepository;
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/role",
     *     tags={"Roles"},
     *     summary="Get Roles List",
     *     description="Get Roles List as Array",
     *     operationId="index_role",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200,description="Get Role List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index_role(): JsonResponse
    {
        try {
            $data = $this->roleRepository->getAll();

            return $this->responseSuccess($data, 'Role List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/role/all",
     *     tags={"Roles"},
     *     summary="All Roles - Accessible",
     *     description="All Roles - Accessible",
     *     operationId="roleAll",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="perPage", description="perPage, eg; 1", example=1, in="query", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="All Roles - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function roleAll(Request $request): JsonResponse
    {
        try {
            $data = $this->roleRepository->getPaginatedData($request->perPage);

            return $this->responseSuccess($data, 'Roles List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/role",
     *     tags={"Roles"},
     *     summary="Create New Role",
     *     description="Create New Role",
     *     operationId="store_role",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="name", type="string", example="superadmin"),
     *              @OA\Property(property="description", type="string", example="manager"),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *
     *      @OA\Response(response=200, description="Create New Rol" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store_role(RoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleRepository->create($request->all());

            return $this->responseSuccess($role, 'New Role Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/role/{id}",
     *     tags={"Roles"},
     *     summary="Show Role Details",
     *     description="Show Role Details",
     *     operationId="show_role",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Show Role Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show_role($id): JsonResponse
    {
        try {
            $data = $this->roleRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Role Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Role Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api_gv/public/api/role/{id}",
     *     tags={"Roles"},
     *     summary="Update Role",
     *     description="Update Role",
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="name", type="string", example="user"),
     *              @OA\Property(property="description", type="string", example="customer"),
     *          ),
     *      ),
     *     operationId="update_role",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Update Role"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update_role(RoleRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->roleRepository->update($id, $request->all());
            if (is_null($data)) {
                return $this->responseError(null, 'Role Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Role Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api_gv/public/api/role/{id}",
     *     tags={"Roles"},
     *     summary="Delete Role",
     *     description="Delete Role",
     *     operationId="destroy_role",
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Delete Role"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy_role($id): JsonResponse
    {
        try {
            $role = $this->roleRepository->getByID($id);
            if (empty($role)) {
                return $this->responseError(null, 'Role Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->roleRepository->delete($id);
            if (! $deleted) {
                return $this->responseError(null, 'Failed to delete the role.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($role, 'Role Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



 /**
     * @OA\PUT(
     *     path="/api_gv/public/api/role/user/{id}",
     *     tags={"Roles"},
     *     summary="Update Role by User",
     *     description="Update Role by User",
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="staff"),
     *          ),
     *      ),
     *     operationId="update_role_user",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Update Role by user"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update_role_user(RoleRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->roleRepository->update_user_role($id, $request->all());
            if (is_null($data)) {
                return $this->responseError(null, 'Role Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Role Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
