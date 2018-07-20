<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'code' => -1,
                'msg' => '您请求的地址不正确',
                'data' => [
                    'path' => $request->path()
                ]
            ]);
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'code' => -1,
                'msg' => '您请求的类型不支持',
                'data' => [
                    'method' => $request->method()
                ]
            ]);
        }
        return response()->json([
            'code' => -2,
            'msg' => '服务器内部故障',
            'data' => env('APP_DEBUG', config('app.debug', false)) ? [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ] : [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]
        ]);
        // return parent::render($request, $e);
    }
}
