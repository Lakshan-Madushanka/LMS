<?php

namespace App\Traits;

trait ApiResponser
{
    use ApiResponseSystem;

    public function showOne(
        $status = 'OK',
        $statusMsg = 'Query Successed !',
        $data,
        $code = 200
    ) {
        $this->successResponce($status, $statusMsg, $data, $code);
    }

    public function showAll(
        $status = 'OK',
        $statusMsg = 'Query Successed !',
        $data,
        $code = 200
    ) {
        if($data instanceof Illuminate\Support\Collection)
        {
            $data = collect($data);
        }
        $this->successResponce($status, $statusMsg, $data, $code);
    }

    public function showError(
        $status = 'Not Found',
        $statusMsg = 'Resourse doesn\'t exists',
        $data,
        $code = 404
    )
    {
        return response()->json(
            [
                'status'         => $status,
                'status_massage' => $statusMsg,
                'data'           => $data
            ],
            $code
        );
    }
}
