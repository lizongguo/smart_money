<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        if (($request->ajax() or $request->wantsJson())
            and ($exception instanceof \Illuminate\Validation\ValidationException)
        ) {
//            return parent::render($request, $exception);
            $errors = $exception->errors();
            $message = array_values($errors)[0][0];
            return response(
                [
                    'status' => 411,
                    'msg' => $message,
                    'errors' => $errors,
                ],
                200
            );
        } else if (($request->ajax() or $request->wantsJson())
            and ($exception instanceof \Exception)){
            return response(
                [
                    'status' => '400',
                    'msg' => $exception->getMessage()
                ],
                400
            );
        }else if (($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)) {
            return response()->view('admin.pages.404');
        }
        return parent::render($request, $exception);
    }
}
