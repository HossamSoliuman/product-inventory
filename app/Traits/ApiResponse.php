<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait ApiResponse
{
    public function apiResponse($data, $meta = null, $success = 1, $code = 200, $pagination = null)
    {
        return response()->json(
            [
                'success' => $success,
                'data' => $data,
                'meta' => $meta,
                'pagination' => $pagination ? [
                    'total' => $pagination->total(),
                    'per_page' => $pagination->perPage(),
                    'current_page' => $pagination->currentPage(),
                    'last_page' => $pagination->lastPage()
                ] : null,
            ],
            $code
        );
    }
}
