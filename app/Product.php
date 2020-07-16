<?php

namespace App;

use App\Image;
use App\Seller;
use App\Subcategory;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    public $transformer = ProductTransformer::class;
    
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'weight',
        'status',
        'price',
        'image',
        'seller_id',
        'subcategory_id'
    ];

    public function estaDisponible(){
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function images(){
        return $this->hasMany(Image::class);
    }

    public function subcategories(){
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

}
