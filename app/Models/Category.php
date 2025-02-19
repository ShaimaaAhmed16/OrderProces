<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'image', 'parent_id'];
    public function child(){
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent(){
        return $this->belongsTo(self::class, 'parent_id' , 'id');
    }
    public function product(){
        return $this->hasMany(Product::class, 'category_id');
    }
}
