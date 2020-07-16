<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('El metodo especificado de la peticion no es valido', 405);
        }
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('No se encontro la url especificada', 404);
        }
        if($exception instanceof AuthorizationException){
            return $this->errorResponse('No pose permisos para ejecutar esta accion', 403);
        }
        if($exception instanceof ValidationException){
            return $this->errorResponse($exception->validator->errors(), 403);
        }


        // Controlar otro tipo de excepciones
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        // Controlar excepciones de tipo QueryException
        if($exception instanceof QueryException){
            $codigo = $exception->errorInfo[1];
            if($codigo == 1451){
                return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque esta relacionado con algun otro recurso', 409);
            }
        }
        // Si estamos en modo debug "desarrollador" necesitamos observar excepciones
        if(config('app.debug')){
            return parent::render($request, $exception);
        }
        // Si estamos en modo de produccion muestra una falla(cambiar en env.  APP_DEBUG=false)
        return $this->errorResponse('Falla inesperada. Intente mas tarde.', 500);
    }

    private function isFrontend($request){
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web'); 
    }   

}
