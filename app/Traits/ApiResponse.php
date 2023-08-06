<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Response in case of successful request
     *
     * @param  mixed $dataArray
     * @param  int $code
     * @return JsonResponse
     */
    public function apiResponseSuccess(mixed $dataArray = [], int $code = Response::HTTP_OK): JsonResponse
    {
        $data = [
            'data' => $dataArray,
            'status' => true,
            'errors' => [],
        ];

        return response()->json($data, $code);
    }

    /**
     * Response in case of an unsuccessful request
     *
     * @param  array $errors format[0 => ['code' => '...', 'message' => '...'], 1 => ['code' => '.
     * ..', 'message' => '...'] ...]
     * @param  array|int $dataArray if not an array, used as response code
     * @param  int $code
     * @return JsonResponse
     */
    public function apiResponseError(array $errors = [], $dataArray = null, int $code = Response::HTTP_OK): JsonResponse
    {
        if (!is_array($dataArray)) {
            $code = $dataArray ?? Response::HTTP_OK;
            $dataArray = [];
        }

        $data = [
            'data' => $dataArray,
            'status' => false,
            'errors' => $errors,
        ];
        return response()->json($data, $code);
    }

    /**
     * Ответ
     *
     * @param  $status
     * @param  mixed $data
     * @param  array $errors
     * @return JsonResponse
     */
    public function apiResponse($status, mixed $data = [], array $errors = []): JsonResponse
    {
        $data = [
            'data' => $data,
            'status' => $status,
            'errors' => $errors,
        ];
        return response()->json($data);
    }
}
