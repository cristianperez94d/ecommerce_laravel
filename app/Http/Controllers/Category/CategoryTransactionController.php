<?php

namespace App\Http\Controllers\Category;

use App\Product;
use App\Category;
use App\Subcategory;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if( !is_object(Category::find($id)) ){
            return $this->errorResponse('El id de la categoria no existe', 404);
        }

        $subcategorias = Category::find($id)->subcategories;
        
        if($subcategorias->count() < 1){
            return $this->errorResponse('La categoria no tiene asiganado subcategorias', 404);
        }
        
        foreach($subcategorias as $subcategoria){
            $arrayTransacciones[] = Subcategory::find($subcategoria->id)->products()
                ->wherehas('transactions')
                ->with('transactions')
                ->get()
                ->pluck('transactions')
                ->collapse();
        }
        
        $transacciones = Arr::collapse($arrayTransacciones);

        return $this->showArray($transacciones);

    }

}
