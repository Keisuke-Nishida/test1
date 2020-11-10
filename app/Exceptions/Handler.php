<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use yii\web\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        $blade_base = 'web.';

        if ($request->segment(1) === 'admin') {
            $blade_base = $request->segment(1).'.';
        }
        // csrf_tokenエラー
        if ($exception instanceof TokenMismatchException) {
            return response()->view($blade_base.'.errors.419', []);
        }
        if ( ! $this->isHttpException($exception))
        {
            return parent::render($request, $exception);
        }

        $status_code = $exception->getStatusCode();

        switch ($status_code)
        {
            case 403:
                return response()->view($blade_base.'.errors.403', [], $status_code);
                break;
            case 404:
                return response()->view($blade_base.'.errors.404', [], $status_code);
                break;
            case 405:
                return response()->view($blade_base.'.errors.405', [], $status_code);
                break;
            case 500:
                return response()->view($blade_base.'.errors.500', ['message' => $exception->getMessage()], $status_code);
                break;
            case 503:
                return response()->view($blade_base.'.errors.503', ['message' => $exception->getMessage()], $status_code);
                break;
            default:
                return parent::render($request, $exception);
        }
    }
}
