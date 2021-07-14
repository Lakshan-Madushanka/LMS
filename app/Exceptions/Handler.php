<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use App\Traits\ApiResponser;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport
        = [
            //
        ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash
        = [
            'current_password',
            'password',
            'password_confirmation',
        ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (AuthenticationException $exception, $request) {
           return $this->unauthenticated($exception, $request);
        });
    }

    public function isFrontEnd(Request $request)
    {
        return $request->acceptsHtml()
            && collect($request->route()->middleware())->contains('web');
    }

    public function unauthenticated(
        $request,
        AuthenticationException $e
    ) {
        if (!$this->isFrontEnd($request)) {
            return $this->showError($e->getMessage(), 'Invalid Login', null,
                $e->getCode());
        }
    }
}
