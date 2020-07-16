<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{

    public function __construct(){
        
        $this->middleware('auth:api')->except(['index','show']);
        $this->middleware('client')->only(['index','show']);
        $this->middleware('transform.input:'.CategoryTransformer::class)->only(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Category::all();

        return $this->showAll($categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => ['required'],
            'description' => ['required'],
            'image' => ['required']
        ];
        
        // validar datos
        $request->validate($reglas);

        // crear la categoria
        $categoria = Category::create($request->all());

        return $this->showOne($categoria);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Category::find($id);

        if( !is_object($categoria) ){
            return $this->errorResponse('La categoria con el id buscado no existe.', 404);
        }

        return $this->showOne($categoria);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $categoria = Category::find($id);
        if( !is_object($categoria) ){
            return $this->errorResponse('La categoria con el id a actualizar no existe.', 404);
        }

        if($request->has('name')){
            $categoria->name = $request->name;
        }

        if($request->has('description')){
            $categoria->description = $request->description;
        }

        if($request->has('image')){
            $categoria->image = $request->image;
        }

        if(!$categoria->isDirty()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        // guardar categoria
        $categoria->save();

        return $this->showOne($categoria);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoria = Category::find($id);
        if( !is_object($categoria) ){
            return $this->errorResponse('La categoria con el id a eliminar no existe.', 404);
        }

        $categoria->delete();

        return $this->showOne($categoria);
    }
}
