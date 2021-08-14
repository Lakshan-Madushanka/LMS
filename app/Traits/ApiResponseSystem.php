<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseSystem
{
    public function successResponce(
        $status,
        $statusMsg,
        $data,
        int $code = 200
    ) {
        $responseData = [
            'status' => $status,
            'status_message' => $statusMsg,
        ];

       if( $data) {
           $responseData['data'] = $data;
       }

        return response()->json($responseData, $code);
    }

    public function errorResponce(
        $status,
        $statusMsg,
        $data,
        int $code = 404
    ) {
        $responseData = [
            'status' => $status,
            'status_message' => $statusMsg,
        ];

        if( $data) {
            $responseData['data'] = $data;
        }

        return response()->json($responseData, $code);
    }
}

