<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if( !is_object(Seller::find($id)) ){
            return $this->errorResponse('El id del vendedor no existe', 404);
        }
        $transacciones = Seller::find($id)->products()
            ->whereHas('transactions')
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse()
        ;
        
        return $this->showAll($transacciones);
    }

}
