<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Seller;
use App\Product;
use App\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Transformers\ProductTransformer;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('transform.input:'.ProductTransformer::class)->only(['store','update']);
    }
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

        $productos = Seller::find($id)->products;

        return $this->showAll($productos);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if( !is_object(User::find($id)) ){
            return $this->errorResponse('El id del usuario no existe', 404);
        }

        $reglas = [
            "name" => ['required'],
            'description' => ['required'],
            'quantity' => ['required', 'integer', 'min:1'],
            'weight' => ['required', 'integer', 'min:1'],
            'price' => ['required','integer', 'min:1'],
            'image'=> ['required','image'],
            'subcategory_id' => ['required', 'integer']
        ];
        
        $request->validate($reglas);

        if( !is_object(Subcategory::find($request->subcategory_id)) ){
            return $this->errorResponse('La subcategoria que trata de ingresar no existe', 404);
        }

        $params = $request->all();

        $params['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $params['image'] = $request->image->store('img/products');
        $params['seller_id'] = $id;

        // crear producto
        $producto = Product::create($params);

        return $this->showOne($producto, 201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $seller_id, $product_id)
    {
        if( !is_object(Seller::find($seller_id)) ){
            return $this->errorResponse('El id del vendedor no existe', 404);
        }
        if( !is_object(Product::find($product_id)) ){
            return $this->errorResponse('El id del producto no existe', 404);
        }

        $vendedor = Seller::find($seller_id);
        $producto = Product::find($product_id);

        // Comprobar si el producto le pertenece al vendor
        $this->verificarVendedorProducto($vendedor, $producto);

        $reglas = [
            'quantity' => ['integer', 'min:1'],
            'weight' => ['integer', 'min:1'],
            'price' => ['integer', 'min:1'],
            'image'=> ['image'],
            'status' => ['in:'.Product::PRODUCTO_DISPONIBLE.','.Product::PRODUCTO_NO_DISPONIBLE],
            'subcategory_id' => ['integer']
        ];

        $validar = Validator::make($request->all(), $reglas);
        if( $validar->fails() ){
            return $this->errorResponse($validar->errors(), 422);
        }
        
        // actualizar los valores del producto 
        if( $request->filled('name') ){
            $producto->name = $request->name;
        }
        if( $request->filled('description') ){
            $producto->name = $request->description;
        }
        if( $request->filled('quantity') ){
            $producto->quantity = $request->quantity;
        }
        if( $request->filled('weight') ){
            $producto->weight = $request->weight;
        }
        if( $request->filled('price') ){
            $producto->price = $request->price;
        }
        if( $request->hasFile('image') ){
            Storage::delete($producto->image);
            $producto->image = $request->image->store('img/products');
        }
        if( $request->filled('status') ){
            $producto->status = $request->status;
        }
        if( $request->filled('subcategory_id') ){
            // comprobar si la subcategoria existe
            if( !is_object(Subcategory::find($request->subcategory_id)) ){
                return $this->errorResponse('La subcategoria que trata de ingresar no existe', 404);
            }
            $producto->subcategory_id = $request->subcategory_id;
        }

        
        // validar si el producto sufrio un cambio para actualizar
        if( $producto->isClean() ){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        // actualizar producto
        $producto->save();

        return $this->showOne($producto);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($seller_id, $product_id)
    {
        if( !is_object(Seller::find($seller_id)) ){
            return $this->errorResponse('El id del vendedor no existe', 404);
        }
        if( !is_object(Product::find($product_id)) ){
            return $this->errorResponse('El id del producto no existe', 404);
        }

        $vendedor = Seller::find($seller_id);
        $producto = Product::find($product_id);

        $this->verificarVendedorProducto($vendedor, $producto);

        Storage::delete($producto->image);

        $producto->delete();

        return $this->showOne($producto);
    }

    // Verificar vendedor del producto
    protected function verificarVendedorProducto(Seller $seller, Product $product){
        
        if( $seller->id != $product->seller_id ){
            throw new HttpException ( 422, 'El vendedor no tiene permiso para actualizar este producto.');
        }

    }
}
