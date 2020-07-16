<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // obtener los usuarios que tiene transacciones activas
        $compradores = Buyer::has('transactions')->get();

        // utilizamos el metodo traits de ApiResponser
        return $this->showAll($compradores);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // obtener el usuario con su id que tiene transacciones activas
        $comprador = Buyer::has('transactions')->findOrFail($id);

        // utilizamos el metodo traits de ApiResponser
        return $this->showOne($comprador);
    }

}
