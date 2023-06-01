<?php

namespace App\Http\Controllers;

use App\Models\comments;
use App\Models\Slide;
use App\Models\products;
use App\Models\type_products;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function getIndex(){
    	$slide =Slide::all();
               $new_product = products::where('new',1)->get();
    	return view('page.trangchu',compact('slide','new_product'));
    }

   public function getLoaiSp($type)
    {
        $sp_theoloai = products::where('id_type', $type)->get();
        $type_product = type_products::all();
        $sp_khac = products::where('id_type', '<>', $type)->paginate(3);

        return view('page.loai_sanpham', compact('sp_theoloai', 'type_product', 'sp_khac'));
    }

    public function getDetail(Request $request)
    {
        $sanpham = products::where('id', $request->id)->first();
        $splienquan = products::where('id', '<>', $sanpham->id, 'and', 'id_type', '=', $sanpham->id_type,)->paginate(3);
        $comments = comments::where('id_product', $request->id)->get();
        return view('page.chitiet_sanpham', compact('sanpham', 'splienquan', 'comments'));
    }



}
