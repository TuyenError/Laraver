<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\products;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getIndex(){
    	$slide =Slide::all();
    	//return view('page.trangchu',['slide'=>$slide]);
               $new_product = products::where('new',1)->get();
        //dd($new_product);
    	return view('page.trangchu',compact('slide','new_product'));
    }



}
