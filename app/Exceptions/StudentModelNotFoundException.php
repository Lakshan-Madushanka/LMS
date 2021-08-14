<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class StudentModelNotFoundException extends Exception
{
    private int $modelId;

    public function __construct(
        $modelId,
        $message = "",
        $code = 404,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->modelId = $modelId;
    }

    public function getModelId()
    {
        return $this->modelId;
    }

    public function setModelId($modelId)
    {
         $this->modelId = $modelId;
    }

    public function getDefaultMessage()
    {
        return "Student does't exist for the identifier {$this->modelId}";
    }
}
