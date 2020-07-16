<?php

namespace App\Http\Controllers\Transaction;

use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if( !is_object(Transaction::find($id)) ){
            return $this->errorResponse('El id de la transaccion no existe.',404);
        }

        $transaccion = Transaction::find($id)->load('product');
        $producto = Product::find($transaccion->product->id)->load('seller');

        return $this->showOne($producto->seller);

    }

}
