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
        return $this->successResponce($status, $statusMsg, $data, $code);
    }

    public function showAll(
        $status = 'OK',
        $statusMsg = 'Query Successed !',
        $data,
        $code = 200
    ) {
        if(empty($data)) {
            return $this->successResponce($status, $statusMsg, $data, $code);
        }
        if(!$data instanceof Illuminate\Support\Collection)
        {
            $data = collect($data);
            if($data->isEmpty()) {
                return $this->successResponce($status, $statusMsg, $data, $code);
            }
        }
        return $this->successResponce($status, $statusMsg, $data, $code);
    }

    public function showError(
        $status = 'Not Found',
        $statusMsg = 'Resourse doesn\'t exists',
        $data,
        $code = 404
    )
    {
        return $this->errorResponce($status, $statusMsg, $data, $code);
    }
}
