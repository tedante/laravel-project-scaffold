<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    \Illuminate\Http\Exceptions\HttpResponseException::class,
    \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    \Illuminate\Validation\ValidationException::class,
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
   * Report or log an exception.
   *
   * @param  \Throwable  $exception
   * @return void
   *
   * @throws \Exception
   */
  public function report(Throwable $exception)
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Throwable  $exception
   * @return \Symfony\Component\HttpFoundation\Response
   *
   * @throws \Throwable
   */
  public function render($request, Throwable $exception)
  {
    $exception = $this->prepareException($exception);

    if ($exception instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
      $exception = $exception->getResponse();
    }

    if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
      $exception = $this->unauthenticated($request, $exception);
    }
    
    if ($exception instanceof \Illuminate\Validation\ValidationException) {
      $exception = $this->convertValidationExceptionToResponse($exception, $request);
    }

    return $this->customApiResponse($exception);
  }

  private function customApiResponse($exception)
  {
    if (method_exists($exception, 'getStatusCode')) {
      $statusCode = $exception->getStatusCode();
    } else {
      $statusCode = 500;
    }

    $response = [];

    switch ($statusCode) {
      case 401:
        $response['message'] = $exception->original['message'] ?: 'Unauthorized';
        break;
      case 403:
        $response['message'] = $exception->getMessage() ?: 'Forbidden';
        break;
      case 404:
        $response['message'] = $exception->getMessage() != '' ? $exception->getMessage() : 'Not Found';
        break;
      case 405:
        $response['message'] = 'Method Not Allowed';
        break;
      case 422:
        $response['message'] = $exception->original['message'] ?? $exception->getMessage();
        $response['errors'] = $exception->original['errors'] ?? null;
        break;
      default:
        $response['message'] = (config('app.debug')) ? $exception->getMessage() : 'Server Error';

        if(config('app.debug')){
          $response['code'] = $exception->getCode();
          $response['trace'] = $exception->getTrace();
        }
        break;
    }
    
    $response['status'] = $statusCode;

    return response()->json($response, $statusCode);
  }
}
