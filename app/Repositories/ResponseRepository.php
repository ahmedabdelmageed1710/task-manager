<?php

namespace App\Repositories;

use Illuminate\Http\JsonResponse;
use App\Repositories\Interfaces\ResponseRepositoryInterface;


class ResponseRepository implements ResponseRepositoryInterface {

    /**
     * success response method.
     *
     * @return JsonResponse
     */

    public function success($result, $message, $code = 200): JsonResponse {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */

    public function error($error, $errorMessages = [], $code = 404): JsonResponse {
        $response = [
            'status' => 'error',
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
