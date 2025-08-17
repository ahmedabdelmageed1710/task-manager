<?php

namespace App\Services;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class AuthService {

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($request)
    {
        return $this->userRepository->login($request);
    }

    public function register($request)
    {
        return $this->userRepository->register($request);
    }

}
