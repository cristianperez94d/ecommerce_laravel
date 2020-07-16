<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {

        $transformedInput = [];
        foreach ($request->request->all() as $input => $value) {
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }
        $request->replace($transformedInput);

        $response = $next($request);

        // transformar la respuesta en caso de error de validacion de datos
        if( isset($response->exception) && $response->exception instanceof ValidationException){
            $data = $response->getdata();
            $transformedErrors = [];

            foreach ($data->error as $field => $error) {
                print_r($error);
                $transformedField = $transformer::transformedAttribute($field);
                $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
            }
            
            $data->error = $transformedErrors;

            $response->setData($data);
        }

        return $response;

    }
}
