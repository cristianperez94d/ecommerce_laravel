<?php

namespace App;

use App\Product;
use App\Transformers\SellerTransformer;

class Seller extends User
{

    public $transformer = SellerTransformer::class;

    public function products(){
        return $this->hasMany(Product::class);
    }


}
