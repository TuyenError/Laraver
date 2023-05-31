<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $table = "products";

    public function type_products(){ //tên bản đặt tên nào cũng đc
        return $this->belongsTo('App\type_products'); //Moder hasMany : một nhiều

    }
    public function bill_detail(){
        return $this ->hasMany('App\bill_detail');
    }

    public function comment()
    {
        return $this->hasMany('App\comment');
    }

}
