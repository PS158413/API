<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Auth related functionalities.
     *
     * @var AuthRepository
     */
    public $authRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $ar)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authRepository = $ar;

        // 60 requests allowed in 1 minute
        $this->middleware(ThrottleRequests::class.':60,1')->only(['register', 'login']);
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/auth/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Login",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="email", type="string", example="manager@groenevingers.com"),
     *              @OA\Property(property="password", type="string", example="password")
     *          ),
     *      ),
     *
     *      @OA\Response(response=200, description="Login"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->responseError(null, 'Invalid Email and Password!', Response::HTTP_UNAUTHORIZED);
            }

            $user = JWTAuth::user();
            $data = $this->respondWithToken($token);
            $data['user']['role'] = $user->role; // Assuming the role information is available in the user object

            return $this->responseSuccess($data, 'Logged In Successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/auth/register",
     *     tags={"Authentication"},
     *     summary="Register Staff",
     *     description="Register New Staff",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="name", type="string", example="Hasan"),
     *              @OA\Property(property="last_name", type="string", example="Hussein"),
     *              @OA\Property(property="city", type="string", example="Eindhoven"),
     *              @OA\Property(property="phone", type="integer", example="000000"),
     *              @OA\Property(property="email", type="string", example="hasan@gmail.com"),
     *              @OA\Property(property="birthday", type="date", example="2000-04-13"),
     *              @OA\Property(property="password", type="string", example="password"),
     *              @OA\Property(property="password_confirmation", type="string", example="password"),
     *          ),
     *      ),
     *
     *      @OA\Response(response=200, description="Register New Staff Data" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $requestData = $request->only('name', 'last_name', 'city', 'phone', 'email', 'birthday', 'password', 'password_confirmation', 'role');

            // Create the user
            $user = $this->authRepository->register($requestData);

            if ($user) {
                $credentials = [
                    'email' => $requestData['email'],
                    'password' => $requestData['password'],
                ];

                if ($token = JWTAuth::attempt($credentials)) {
                    $data = $this->respondWithToken($token);

                    return $this->responseSuccess($data, 'User Registered Successfully', Response::HTTP_OK);
                }
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage().' '.$e->getLine(), Response::HTTP_INTERNAL_SERVER_ERROR);
            dd($e->getMessage());
        }
    }

    /**
     * @OA\GET(
     *     path="/api_gv/public/api/auth/user",
     *     tags={"Authentication"},
     *     summary="Authenticated User Profile",
     *     description="Authenticated User Profile",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Authenticated User Profile" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function user(): JsonResponse
    {
        try {
            $user = $this->guard()->user();
            $data = [
                'user' => $user,
                'role' => $user->role()->first(),
            ];

            return $this->responseSuccess($data, 'Profile Fetched Successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/auth/logout",
     *     tags={"Authentication"},
     *     summary="Logout",
     *     description="Logout",
     *
     *     @OA\Response(response=200, description="Logout" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::parseToken()->invalidate(); // Invalidate the JWT token

            return $this->responseSuccess(null, 'Logged out successfully !');
        } catch (JWTException $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api_gv/public/api/auth/refresh",
     *     tags={"Authentication"},
     *     summary="Refresh",
     *     description="Refresh",
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(response=200, description="Refresh" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function refresh(): JsonResponse
    {
        try {
            $token = JWTAuth::getToken();

            if (! $token) {
                return $this->responseError(null, 'Token not provided', Response::HTTP_BAD_REQUEST);
            }

            $refreshedToken = JWTAuth::refresh($token);

            if (! $refreshedToken) {
                return $this->responseError(null, 'Invalid token', Response::HTTP_UNAUTHORIZED);
            }

            $data = $this->respondWithToken($refreshedToken);

            return $this->responseSuccess($data, 'Token Refreshed Successfully!');
        } catch (JWTException $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     */
    protected function respondWithToken($token): array
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 * 24, // 1440 Minutes = 24 Hours
            'user' => Auth::user(),
        ];

        return $data;
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard(): \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
    {
        return Auth::guard();
    }
}
