<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Validator;
use App\Services\AuthService;
use App\Repositories\ResponseRepository;
use App\Helpers\HttpStatusCodes;
use App\Http\Requests\LoginRequest; // Assuming you have a request class for login validation
use App\Http\Requests\RegisterRequest; // Assuming you have a request class for registration validation
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
            if ($res['statusCode'] !== 200) {
                return $this->response->error($res['message'], $res['data'], $res['statusCode']);
            }
            return $this->response->success($res['data'], $res['message'], $res['statusCode']);
        } catch (ValidationException $e) {
            return $this->response->error('The given data was invalid.', $e->validator->errors(), HttpStatusCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function register(RegisterRequest $request)
    {
        // Validate the incoming request data (email and password).

        try {
            // Attempt to register the user
            $res = $this->authService->register($request);
            return $this->response->success($res['data'], $res['message'], $res['statusCode']);
        } catch (\Exception $e) {
            return $this->response->error('Registration failed', $e->getMessage(), HttpStatusCodes::HTTP_BAD_REQUEST);
        }
    }
}
