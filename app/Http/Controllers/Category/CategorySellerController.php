<?php

namespace App\Http\Controllers\Category;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if ( !is_object(Category::find($id)) ) {
            return $this->errorResponse('El id de la categoria no existe.', 404);
        }

        $subcategorias = Category::find($id)->subcategories()
            ->with('products.seller')
            ->get()
            ->pluck('products');

        ;
        

        for ($i = 0 ; $i < count($subcategorias); $i++) {

            foreach ($subcategorias[$i] as $producto) {
                $vendedores[] = $producto->seller;
            }

        }

        $vendedores = array_values(array_unique($vendedores));

        return $this->showArray($vendedores);
        // return $this->showAll($vendedores);
    }

}
