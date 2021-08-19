<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseSystem
{
    public function successResponce(
        $data,
        int $code = 200,
        $status = 'ok',
        $statusMsg = 'Query successed !'
    ) {
        $responseData = [
            'status' => $status,
            'status_message' => $statusMsg,
        ];

        if ($data) {
            $responseData['data'] = $data;
        }

        return response()->json($responseData, $code);
    }

    public function errorResponce(
        $data,
        int $code = 500,
        $status = 'Error',
        $statusMsg = 'Error occured'
    ) {
        $responseData = [
            'status' => $status,
            'status_message' => $statusMsg,
        ];

        if ($data) {
            $responseData['data'] = $data;
        }

        return response()->json($responseData, $code);
    }
}

