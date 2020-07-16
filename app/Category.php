<?php

namespace App;

use App\Subcategory;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    public $transformer = CategoryTransformer::class;
    // use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'image'
    ]; 

    public function subcategories(){
        return $this->hasMany(Subcategory::class);
    }
}
 