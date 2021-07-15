<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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
        $this->renderable(function (
            ValidationException $exception,
            $request
        ) {
            return $this->convertValidationExceptionToResponse($exception,
                $request);
        });

        $this->renderable(function (
            AuthenticationException $exception,
            $request
        ) {
            return $this->unAuthenticated($exception, $request);
        });

        $this->renderable(function (
            AuthorizationException $exception,
            $request
        ) {
            return $this->unAuthorized($exception, $request);
        });

        $this->renderable(function (
            RouteNotFoundException $exception,
            $request
        ) {
            return $this->showError($exception->getMessage(), 'Invalid URL',
                null, $exception->getCode());
        });

        $this->renderable(function (
            ModelNotFoundException $exception,
            $request
        ) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->showError(
                $exception->getMessage(),
                "Requested $model doesn't exist",
                null, $exception->getCode()
            );
        });

        $this->renderable(function (
            MethodNotAllowedException $exception,
            $request
        ) {
            return $this->showError($exception->getMessage(), 'Invalid URL',
                null, $exception->getCode());
        });

        $this->renderable(function (
            QueryException $exception,
            $request
        ) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->showError($exception->getMessage(),
                    'Operation cannot proceed [Record is associated with other records',
                    null, $exception->getCode());
            }
        });

        $this->renderable(function (
            HttpException $exception,
            $request
        ) {
            return $this->showError($exception->getMessage(),
                'General Error',
                null, $exception->getCode());

        });

        return $this->showError('Error',
            'Something went wrong, please try again later',
            null, 500);
    }

    //end of register

    public function isFrontEnd(Request $request)
    {
        return $request->acceptsHtml()
            && collect($request->route()->middleware())->contains('web');
    }

    public function unAuthenticated(
        $request,
        AuthenticationException $e
    ) {
        if (!$this->isFrontEnd($request)) {
            return $this->showError($e->getMessage(), 'Invalid Login', null,
                $e->getCode());
        }
    }

    public function unAuthorized(
        $request,
        AuthorizationException $e
    ) {
        if (!$this->isFrontEnd($request)) {
            return $this->showError($e->getMessage(), 'Invalid Login', null,
                $e->getCode());
        } else {
            return redirect()->guest('login');
        }
    }

    public function convertValidationExceptionToResponse(
        ValidationException $e,
        $request
    ) {
        $errors = $e->errors();
        if ($this->isFrontEnd()) {
            $request->ajax()
                ? response()->json(['messages' => $errors], $e->getCode())
                :
                redirect()->back()->withInput()->withErrors();
        }

        return $this->showError($e->getMessage(), 'Validation Error', $errors,
            $e->getCode());
    }
}
