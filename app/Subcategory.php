<?php

namespace App;

use App\Product;
use App\Category;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\SubcategoryTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    public $transformer = SubcategoryTransformer::class;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'category_id'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
