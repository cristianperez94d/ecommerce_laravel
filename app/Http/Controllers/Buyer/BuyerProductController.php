<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
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

        $productos = Buyer::find($id)->transactions()
            ->with('product')
            ->get()
            ->pluck('product');

        return $this->showAll($productos);
        
    }


}
