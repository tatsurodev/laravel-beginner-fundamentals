<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;

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
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // ModelNotFoundException: recordが見つからない等のエラー
        if ($request->expectsJson() && $exception instanceof ModelNotFoundException) {
            // fallback routeを返す
            return Route::respondWithRoute('api.fallback');
        }
        // dd(get_class($exception));
        if ($request->expectsJson() && $exception instanceof AuthorizationException) {
            return response()->json(['message' => $exception->getMessage(), 403]);
        }
        return parent::render($request, $exception);
    }
}
