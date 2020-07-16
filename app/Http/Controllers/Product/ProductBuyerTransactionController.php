<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('transform.input:'.TransactionTransformer::class)->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $product_id, $user_id)
    {
        if( !is_object(User::find($user_id)) ){
            return $this->errorResponse('El id de usuario no existe', 404);
        }
        if( !is_object(Product::find($product_id)) ){
            return $this->errorResponse('El id de producto no existe', 404);
        }

        $comprador = User::find($user_id);
        $producto = Product::find($product_id);

        $reglas = [
            'quantity' => ['required', 'integer', 'min:1']
        ];

        $request->validate($reglas);

        if($comprador->id === $producto->seller_id){
            return $this->errorResponse('El comprador debe ser diferente al vendedor', 404);
        }

        if( !$comprador->esVerificado() ){
            return $this->errorResponse('El comprador debe ser un usuario verificado', 409);
        }

        if( !$producto->seller->esVerificado() ){
            return $this->errorResponse('El vendedor debe ser un usuario verificado', 409);
        }

        if( !$producto->estaDisponible() ){
            return $this->errorResponse('El Producto no esta dispoonible', 409);
        }

        if( $producto->quantity < $request->quantity ){
            return $this->errorResponse('El producto no tiene la cantidad disponible', 409);
        }

        return DB::transaction(function() use($request, $producto, $comprador){
            $producto->quantity -= $request->quantity;
            $producto->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $comprador->id,
                'product_id' => $producto->id
            ]);
            
            return $this->showOne($transaction, 201);

        } );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
