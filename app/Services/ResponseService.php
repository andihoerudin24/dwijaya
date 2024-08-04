<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ResponseService
{
    /**
     * Generate a JSON response for success.
     *
     * @param  array  $data
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success(array $data, int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data
        ], $status);
    }

    /**
     * Generate a JSON response for errors.
     *
     * @param  array  $errors
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(array $errors, int $status = 422): JsonResponse
    {
        return response()->json(['errors' => $errors], $status);
    }
}
