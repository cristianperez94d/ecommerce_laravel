<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // obtener los usuarios que tiene productos a la venta
        $vendedores = Seller::has('products')->get();

        // utilizamos el metodo traits de ApiResponser
        return $this->showAll($vendedores);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // obtener el usuario con su id que tiene productos a la venta
        $vendedor = Seller::has('products')->findOrFail($id);

        // utilizamos el metodo traits de ApiResponser
        return $this->showOne($vendedor);
    }

}
