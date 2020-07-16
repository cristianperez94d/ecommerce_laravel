<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if( !is_object(Product::find($id)) ){
            return $this->errorResponse('El id del producto no existe', 404);
        }

        $comprador = Product::find($id)->transactions()->with('buyer')
            ->get()
            ->pluck('buyer')
            ->unique()
            ->values()
        ;

        return $this->showAll($comprador);
    }

}
