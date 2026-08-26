<?php

namespace App\Traits;

trait ApiResponse
{
    public function success($data, $message, $code)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function error($data, $message, $code){
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
