<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

trait sendApiResponse
{
    public function sendApiResponse(mixed $data = '', string $message = 'success', string $errorType = '', array $extra = [], int $code = 200): JsonResponse
    {
        $response = [
            'message'        => $message,
            'success'        => $errorType === '',
            'error_type'     => $errorType,
            'execution_time' => (float)number_format(microtime(true) - LARAVEL_START, 3),
        ] + $extra;

        if ($data instanceof ResourceCollection && $data->resource instanceof AbstractPaginator) {
            $data = array_merge($data->resource->toArray(), $data->additional);
        } elseif (!($data instanceof LengthAwarePaginator)) {
            $data = compact('data');
        } else {
            $data = $data->toArray();
        }
        $response += $data;

        return response()->json($response, $code);
    }
}


