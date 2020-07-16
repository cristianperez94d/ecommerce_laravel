<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('transform.input:'.UserTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     * Mostrar una lista del recurso.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        // utilizamos el metodo traits de ApiResponser
        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     * Almacene un recurso recién creado en el almacenamiento.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed']
        ];

        // validar datos
        $request->validate($reglas);

        $params = $request->all();
        $params['password'] = password_hash($request->password, PASSWORD_DEFAULT);
        $params['verified'] = User::USUARIO_NO_VERIFICADO;
        $params['verification_token'] = User::generarVerificationToken();
        $params['admin'] = User::USUARIO_REGULAR;

        // crear usuario
        $usuario = User::create($params);
        
        // utilizamos el metodo traits de ApiResponser
        return $this->showOne($usuario, 201);
    }

    /**
     * Display the specified resource.
     * Mostrar el recurso especificado.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::find($id);

        if( !is_object($usuario) ){
            return $this->errorResponse('El id de usuario que busca no existe!!', 404);
        }

        // utilizamos el metodo traits de ApiResponser
        return $this->showOne($usuario);
    }

    /**
     * Update the specified resource in storage.
     * Actualiza el recurso especificado en el almacenamiento.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuario = User::find($id);

        if( !is_object($usuario) ){
            return $this->errorResponse('El id de usuario que busca no existe!!', 404);
        }

        $reglas = [
            'email' => ['email', 'unique:users,email,'.$usuario->id], // exceptuar email actual de usuario para actualizar
            'password' => ['min:6', 'confirmed'],
            'admin' => ['in:'.User::USUARIO_ADMINISTRADOR.','.User::USUARIO_REGULAR]
        ];

        // validar datos
        $request->validate($reglas);

        if($request->has('name')){
            $usuario->name = $request->name;
        }

        if($request->has('email') && $usuario->email != $request->email){
            $usuario->verified = User::USUARIO_NO_VERIFICADO;
            $usuario->verification_token = User::generarVerificationToken();
            $usuario->email = $request->email;
        }

        if($request->has('password')){
            $usuario->password = password_hash($request->password, PASSWORD_DEFAULT);
        }

        if($request->has('admin')){
            if(!$usuario->esVerificado()){
                return $this->errorResponse('Unicamente usuario verificados pueden cambiar su valor de administrador', 409);
            }
            $usuario->admin = $request->admin;
        }

        if(!$usuario->isDirty()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        // guardar los cambios
        $usuario->save();

        // utilizamos el metodo traits de ApiResponser
        return $this->showOne($usuario);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::find($id);
        if( !is_object($usuario) ){
            return $this->errorResponse('El id de usuario que busca no existe!!', 404);
        }

        $usuario->delete();

        // utilizamos el metodo traits de ApiResponser
        return $this->showOne($usuario);
    }
    
    public function verify($token){
        $usuario = User::where('verification_token', $token)->first();
        
        if( !is_object($usuario) ){
            return $this->errorResponse('El usuario que busca no existe!!', 404);
        }

        $usuario->verified = User::USUARIO_VERIFICADO;
        // Remover el token actual una vez verificado
        $usuario->verification_token = null;

        $usuario->save();

        return $this->showMessage('La cuenta ha sido verificada');

    }

    public function resend(User $user){
        if ($user->esVerificado()) {
            return $this->errorResponse('Este usuario ya ha sido verificado', 409);
        }

        // intentar reenviar 5 veces en caso de fallar el evio
        retry( 5, function() use($user) {
            Mail::to($user)->send( new UserCreated($user) );  
        }, 100 );

        return $this->showMessage('El correo de verificacion se ha reenviado');
    }


}
