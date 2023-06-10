<?php

namespace App\Http\Controllers;

use App\Models\comments;
use App\Models\Slide;
use App\Models\products;
use App\Models\Cart;
use App\Models\type_products;
use App\Models\User;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use App\Models\bill_detail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



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

    public function getIndexAdmin(){
         $product = products::all();
         return view('page.admin')->with(['products' => $product, 'sumSold' => count(bill_detail::all())]);

    }
    public function getAdminAdd()
    {
    return view('page.formAdd');
    }

    public function postAdminAdd(Request $request)
    {
        $products = new products();
        if ($request->hasFile('inputImage')) {
        $file = $request->file('inputImage');
        $fileName = $file->getClientOriginalName('inputImage');
        $file->move('source/image/product', $fileName);
        }
        $file_name = null;
        if ($request->file('inputImage') != null) {
        $file_name = $request->file('inputImage')->getClientOriginalName();
        }

        $products->name = $request->inputName;
        $products->image = $file_name;
        $products->description = $request->inputDescription;
        $products->unit_price = $request->inputPrice;
        $products->promotion_price = $request->inputPromotionPrice;
        $products->unit = $request->inputUnit;
        $products->new = $request->inputNew;
        $products->id_type = $request->inputType;
        $products->save();
        return $this->getIndexAdmin();
    }

    public function getAdminEdit($id){
        $products =  products::findOrFail($id)->first();
        return view('page.formEdit')->with('products', $products);
    }
    public function postAdminEdit(Request $request)
    {
        $id = $request->editId;
        $products= products::find($id);
        if($request->hasFile('editImage')){
            $file = $request->file('editImage');
            $fileName = $file->getClientOriginalName('eidtImage');
            $file->move('source/image/product', $fileName);
        }
        if ($request->file('editImage') != null) {
            $products->image = $fileName;
        }
        $products->name = $request->editName;
        $products->description = $request->editDescription;
        $products->unit_price = $request->editPrice;
        $products->promotion_price = $request->editPromotionPrice;
        $products->unit = $request->editUnit;
        $products->new = $request->editNew;
        $products->id_type = $request->editType;
        $products->save();
        return $this->getIndexAdmin();
    }

    public function postAdminDelete($id)
    {
    $products = products::find($id);
    $products->delete();
    return $this->getIndexAdmin();
    }
    public function getAddToCart(Request $req, $id)
    {
        if (Session::has('user')) {
            $products = products::find($id);
            if ($products) {
                $oldCart = session('cart') ? session('cart') : null;
                $cart = new Cart($oldCart);
                $cart->add($products, $id);
                $req->session()->put('cart', $cart);
                return redirect()->back();
            } else {
                return '<script>alert("Không tìm thấy sản phẩm này.");window.location.assign("/");</script>';
            }
        } else {
            return '<script>alert("Vui lòng đăng nhập để sử dụng chức năng này.");window.location.assign("/login");</script>';
        }
    }


}
