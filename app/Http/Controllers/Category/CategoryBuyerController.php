<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Subcategory;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
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

        $subcategorias = Category::find($id)->subcategories()
            ->with('products.transactions.buyer')
            ->get()
            ->pluck('products')
            ->collapse()
            ->unique()
            ->values()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique()
            ->values()
            ;

        return $this->showAll($subcategorias);
        
    }

}
