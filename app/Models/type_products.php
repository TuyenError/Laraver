<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class type_products extends Model
{
    use HasFactory;
    protected $table = "type_products"; //Tên bản

    public function products(){ //tên bản đặt tên nào cũng đc
        return $this->hasMany('App\products'); //Moder hasMany : một nhiều

    }
}
