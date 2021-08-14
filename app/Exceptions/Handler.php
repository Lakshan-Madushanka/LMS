<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            return $this->unAuthenticated($request, $exception);
        });

        $this->renderable(function (
            AuthorizationException $exception,
            $request
        ) {
            return $this->unAuthorized($exception, $request);
        });

        $this->renderable(function (
            AccessDeniedHttpException $exception,
            $request
        ) {
            return $this->accessDenied($exception, $request);
        });

        $this->renderable(function (
            ModelNotFoundException $exception,
            $request
        ) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->showError(
                $exception->getMessage(),
                "Requested $model doesn't exist",
                null, $exception->getStatusCode()
            );
        });

        $this->renderable(function (
            NotFoundHttpException $exception,
            $request
        ) {
            return $this->showError('URL Error', 'Invalid URL',
                null, 404);
        });

        $this->renderable(function (
            MethodNotAllowedHttpException $exception,
            $request
        ) {
            return $this->showError('Method Error',
                'Method doesn\'t support for this url',
                null, 405);
        });

        if (!config('app.debug')) {

            $this->renderable(function (
                QueryException $exception,
                $request
            ) {
                $errorCode = $exception->errorInfo[1];
                if ($errorCode == 1451) {
                    return $this->showError('Foreign key violation',
                        'Operation cannot proceed [Record is associated with other records',
                        null, Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            });

            $this->renderable(function (
                HttpException $exception,
                $request
            ) {
                return $this->showError($exception->getMessage(),
                    'Error',
                    'Something went wrong',
                    null, $exception->getStatusCode());

            });

        }
        return $this->registerCustomMethods();
    }

    //end of register

    public function convertValidationExceptionToResponse(
        ValidationException $e,
        $request
    ) {
        $errors = $e->errors();
        if ($this->isFrontEnd($request)) {
            $request->ajax()
                ? response()->json(['messages' => $errors], $e->status)
                :
                redirect()->back()->withInput(Arr::except($request->input(),
                    $this->dontFlash)->withErrors());
        }
        return $this->showError($e->getMessage(), 'Validation Error', $errors,
            $e->status);
    }

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
            return $this->showError($e->getMessage(),
                'Login required to do the operation', null,
                Response::HTTP_UNAUTHORIZED);
        }
        return redirect()->guest($e->redirectTo() ?? route('login'));
    }


    //laravel 8 autorization exception for authorization
    public function accessDenied(
        AccessDeniedHttpException $e,
        $request
    ) {

        if (!$this->isFrontEnd($request)) {
            return $this->showError($e->getMessage(),
                'You dont have neccessity authorization access', null,
                Response::HTTP_FORBIDDEN);
        } else {
            new AccessDeniedHttpException($e->getMessage(), $e);
        }
    }

    public function unAuthorized(
        AuthorizationException $e,
        $request
    ) {

        if (!$this->isFrontEnd($request)) {
            return $this->showError($e->getMessage(), 'Access Denied', null,
                Response::HTTP_FORBIDDEN);
        } else {
            new AccessDeniedHttpException($e->getMessage(), $e);
        }
    }

    public function registerCustomMethods()
    {
        $this->renderable(function (
            StudentModelNotFoundException $e,
            $request
        ) {
            $modelID = $e->getModelId();
            return $this->showError('Error',
                $e->getDefaultMessage(), null,
                Response::HTTP_NOT_FOUND);
        });
    }
}
