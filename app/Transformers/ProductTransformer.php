<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identificador' => (int)$product->id,
            'producto' => (string)$product->name,
            'descripcion' => (string)$product->description,
            'disponibles' => (int)$product->quantity,
            'peso' => (int)$product->weight,
            'estado' => (string)$product->status,
            'precio' => (int)$product->price,
            'foto' => url("{$product->image}"),
            'vendedor' => (int)$product->seller_id,
            'subcategoria' => (int)$product->subcategory_id,
            'fechaCreacion' => (string)$product->created_at,
            'fechaActualizacion' => (string)$product->updated_at,
            'fechaEliminacion' => isset($product->deleted_at) ? (string)$product->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel' => 'products.buyers',
                    'href' => route('products.buyers.index' , $product->id),
                ],
                [
                    'rel' => 'products.categories ',
                    'href' => route('products.categories.index' , $product->id),
                ],
                [
                    'rel' => 'products.transactions ',
                    'href' => route('products.transactions.index' , $product->id),
                ],
                [
                    'rel' => 'seller ',
                    'href' => route('sellers.show' , $product->seller_id),
                ],
                
            ],
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'producto' => 'name',
            'descripcion' => 'description',
            'disponibles' => 'quantity',
            'peso' => 'weight',
            'estado' => 'status',
            'precio' => 'price',
            'foto' => 'image',
            'vendedor' => 'seller_id',
            'subcategoria' => 'subcategory_id',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',    
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'name' => 'producto',
            'description' => 'descripcion',
            'quantity' => 'disponibles',
            'weight' => 'peso',
            'status' => 'estado',
            'price' => 'precio',
            'image' => 'foto',
            'seller_id' => 'vendedor',
            'subcategory_id' => 'subcategoria',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion',    
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }
}
