<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if( !is_object(Buyer::find($id)) ){
            return $this->errorResponse('El id del comprador no existe', 404);
        }
        
        // retorna el comprador con las transacciones realizadas transacciones
        $transacciones = Buyer::find($id)->load('transactions');

        return $this->showOne($transacciones); 
        
    }

}
