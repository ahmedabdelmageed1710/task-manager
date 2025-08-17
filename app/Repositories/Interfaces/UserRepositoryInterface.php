<?php
namespace App\Repositories\Interfaces;

Interface UserRepositoryInterface{

    public function login($request);
    public function register($request);
    public function userByToken();

    public function findUserById($id);
    public function findUserByEmail($email);
    public function updateUser($data, $user);
}
