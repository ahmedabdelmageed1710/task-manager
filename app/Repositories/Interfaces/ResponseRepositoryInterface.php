<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Response;

/**
 * Interface ResponseRepositoryInterface
 *
 * @package App\Repositories\Interfaces
 */
interface ResponseRepositoryInterface {

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @param $code
     *
     * @return \Illuminate\Http\Response
     */
    public function success($result, $message, $code);

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     *
     * @return \Illuminate\Http\Response
     */
    public function error($error, $errorMessages = [], $code = 404);

}
