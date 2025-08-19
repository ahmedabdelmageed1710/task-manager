<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Store a new User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUser(array $data): User
    {
        // Create a new user instance with the provided data.
       return User::create($data);
    }

    /**
    * Register a User.
    *
    * @return \Illuminate\Http\JsonResponse
    */

    public function login($request)
    {
        // Convert array to the expected format for Auth::attempt
        $credentials = is_array($request) ? $request : $request->only('email', 'password');

        // Attempt to authenticate the user using their email and password.
        if (!Auth::attempt($credentials)) {
            $user = $this->findUserByEmail($credentials['email']);
            if(!$user){
                throw new \Exception('No user exists with this email address.', HttpStatusCodes::HTTP_NOT_FOUND);
            }else if(!Hash::check($credentials['password'], $user->password)) {
                throw new \Exception('Wrong password.', HttpStatusCodes::HTTP_BAD_REQUEST);
            }
        }

        $user = Auth::user(); // Get the authenticated user instance.
        $token = $user->createToken('access-token')->accessToken; // Create an access token for the user.
        // Return a response with the user data and the access token.
        return [ 'statusCode' => 200,  'message' => 'User logged in successfully.', 'data' => [ 'user' => $user, 'access_token' => $token ] ];
    }



    public function register($request)
    {
        $user = $this->storeUser($request);
        $token = $user->createToken('access-token')->accessToken; // Create an access token for the user.
        return [ 'message' => 'User registered successfully.', 'data' => [ 'user' => $user, 'token' => $token ], 'statusCode' => 201 ];
    }
}
