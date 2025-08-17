<?php

namespace App\Services;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService {

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findUserById($id)
    {
        return $this->userRepository->findUserById($id);
    }
    
}
