<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;
use App\Helpers\HttpStatusCodes;
use App\Http\Requests\AuthRequests\LoginRequest; // Assuming you have a request class for login validation
use App\Http\Requests\AuthRequests\RegisterRequest; // Assuming you have a request class for registration validation
use App\Repositories\ResponseRepository; // Assuming you have a ResponseRepository for handling responses

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService, ResponseRepository $response)
    {
        parent::__construct($response);
        $this->authService = $authService;
    }

    /**
     * Handle user login and issue an access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
       // Attempt to authenticate the user using their email and password.
        try {
            $res = $this->authService->login($request->validated());
            return $this->response->success($res['data'], $res['message'], $res['statusCode']);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

    public function register(RegisterRequest $request)
    {
        // Validate the incoming request data (email and password).

        try {
            // Attempt to register the user
            $res = $this->authService->register($request->validated());
            return $this->response->success($res['data'], $res['message'], $res['statusCode']);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }
}
