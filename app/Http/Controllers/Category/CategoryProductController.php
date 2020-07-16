<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryProductController extends ApiController
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
        $productos = Category::find($id)->subcategories()
            ->whereHas('products')
            ->with('products')
            ->get()
            ->pluck('products')
            ->collapse();

        return $this->showAll($productos);

    }

}
