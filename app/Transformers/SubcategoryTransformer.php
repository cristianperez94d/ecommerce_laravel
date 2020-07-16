<?php

namespace App\Transformers;

use App\Subcategory;
use League\Fractal\TransformerAbstract;

class SubcategoryTransformer extends TransformerAbstract
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
    public function transform(Subcategory $subcategory)
    {
        return [
            'identificador' => (int)$subcategory->id,
            'subcategoria' => (string)$subcategory->name,
            'descripcion' => (string)$subcategory->description,
            'categoria' => (int)$subcategory->category_id,
            'fechaCreacion' => (string)$subcategory->created_at,
            'fechaActualizacion' => (string)$subcategory->updated_at,
            'fechaEliminacion' => isset($subcategory->deleted_at) ? (string)$subcategory->deleted_at : null,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'subcategoria' => 'name',
            'descripcion' => 'description',
            'categoria' => 'category_id',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',    
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'name' => 'subcategoria',
            'description' => 'descripcion',
            'category_id' => 'categoria',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion',    
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null ;
    }
}
