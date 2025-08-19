<?php
namespace App\Repositories\Interfaces;

Interface UserRepositoryInterface{

    public function login($request);
    public function register($request);
}
