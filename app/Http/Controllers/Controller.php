<?php

namespace App\Http\Controllers;
use App\Repositories\ResponseRepository; // Assuming you have a ResponseRepository for handling responses

abstract class Controller
{
    /**
     * @var Response
     */
    protected $response;

    public function __construct(ResponseRepository $response)
    {
        $this->response = $response;
    }
}
