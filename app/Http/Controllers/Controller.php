<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    public function success($data = [], $status_code = Response::HTTP_OK)
    {
        return response()->json([
            'success' => true,
            'payload' => $data
        ], $status_code);
    }

    public function error($data = [], $status_code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'success' => false,
            'payload' => $data
        ], $status_code);
    }
}
