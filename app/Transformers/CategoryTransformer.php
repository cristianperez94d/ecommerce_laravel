<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
    public function transform(Category $category)
    {
        return [
            'identificador' => (int)$category->id,
            'categoria' => (string)$category->name,
            'descripcion' => (string)$category->description,
            'imagen' => url("img/categories/{$category->image}"),
            'fechaCreacion' => (string)$category->created_at,
            'fechaActualizacion' => (string)$category->updated_at,
            'fechaEliminacion' => isset($category->deleted_at) ? (string)$category->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id),
                ],
                [
                    'rel' => 'categories.buyers',
                    'href' => route('categories.buyers.index' , $category->id),
                ],
                [
                    'rel' => 'categories.products ',
                    'href' => route('categories.products.index' , $category->id),
                ],
                [
                    'rel' => 'categories.sellers',
                    'href' => route('categories.sellers.index' , $category->id),
                ],
                [
                    'rel' => 'categories.transactions ',
                    'href' => route('categories.transactions.index' , $category->id),
                ],
                
            ],
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'categoria' => 'name',
            'descripcion' => 'description',
            'imagen' => 'image',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'name' => 'categoria',
            'description' => 'descripcion',
            'image' => 'imagen',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }
}
