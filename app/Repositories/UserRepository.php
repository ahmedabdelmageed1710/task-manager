<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

    public function findUserById($id)
    {
        return User::find($id);
    }

    public function findUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function updateUser($data, $user)
    {
        return $user->update($data);
    }

    public function allUsers()
    {
        return User::where('status',true)->get();
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
                return [ 'statusCode' => 404, 'data' => null, 'message' => 'No user exists with this email address.' ];
            }else if(!Hash::check($credentials['password'], $user->password)) {
                return [ 'statusCode' => 400, 'data' => null, 'message' => 'Wrong password.' ];
            }
        }

        $user = Auth::user(); // Get the authenticated user instance.
        $token = $user->createToken('access-token')->accessToken; // Create an access token for the user.
        // Return a response with the user data and the access token.
        return [ 'statusCode' => 200,  'message' => 'User logged in successfully.', 'data' => [ 'user' => $user, 'access_token' => $token ] ];
    }



    public function register($request)
    {
        $user = $this->storeUser($request->all());
        $token = $user->createToken('access-token')->accessToken; // Create an access token for the user.
        return [ 'message' => 'User registered successfully.', 'data' => [ 'user' => $user, 'token' => $token ], 'statusCode' => 201 ];
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function userByToken() {
        $user = auth('api')->user();
        return $user;
    }
}
