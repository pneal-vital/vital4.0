<?php namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use \Lang;
use \Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
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
        return parent::report($e);
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
        if($e instanceof QueryException and $e->getCode() == 23000) {
            $errors = array();
            $errors[] = $e->errorInfo[2];
            $requestUri = $request->getRequestUri();
            //dd(__METHOD__.'('.__LINE__.')',compact('request','e','errors','requestUri'));
            Log::warning('QueryException', $e->errorInfo);
            return redirect()->back()
                ->withErrors($errors);
        }
        elseif($e instanceof HttpException and $e->getStatusCode() == 401) {
            $errors = array();
            $errors[] = $e->getMessage();
            $requestUri = "/".explode("/",$request->getRequestUri())[1];
            //dd(__METHOD__.'('.__LINE__.')',compact('request','e','errors','requestUri'));
            Log::warning('HttpException - '.$e->getMessage(), $e->getHeaders());
            return redirect()->back()
                ->withErrors($errors);
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('request','e','errors'));
        return parent::render($request, $e);
    }
}
