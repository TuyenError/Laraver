<?php

namespace App\Http\Controllers;

use App\Models\bills;
use App\Models\comments;
use App\Models\customer;
use App\Models\Slide;
use App\Models\products;
use App\Models\Cart;
use App\Models\type_products;
use App\Models\User;
use App\Models\wishlists;
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
    public function getDaItemCart($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if (count($cart->items)> 0 && Session::has('cart')){
            Session::put('cart', $cart);
        }else{
            Session::forget('cart');
        }
        return redirect()->back();
    }

    public function getCheckout()
    {
        if (Session::has('cart')) {
            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);
            return view('page.checkout')->with([
                'cart' => Session::get('cart'),
                'product_cart' => $cart->items,
                'totalPrice' => $cart->totalPrice,
                'totalQty' => $cart->totalQty
            ]);;
        } else {
            return redirect('/master');
        }
    }
    public function postCheckout(Request $req)
{
    $cart = Session::get('cart');
    $customer = new customer;
    $customer->name = $req->full_name;
    $customer->gender = $req->gender;
    $customer->email = $req->email;
    $customer->address = $req->address;
    $customer->phone_number = $req->phone;

    if (isset($req->notes)) {
        $customer->note = $req->notes;
    } else {
        $customer->note = "Không có ghi chú gì";
    }

    $customer->save();

    $bill = new bills;
    $bill->id_customer = $customer->id;
    $bill->date_order = date('Y-m-d');
    $bill->total = $cart->totalPrice;
    $bill->payment = $req->payment_method;

    if (isset($req->notes)) {
        $bill->note = $req->notes;
    } else {
        $bill->note = "Không có ghi chú gì";
    }

    $bill->save();

    foreach ($cart->items as $key => $value) {
        $bill_detail = new bill_detail;
        $bill_detail->id_bill = $bill->id;
        $bill_detail->id_product = $key; //$value['item']['id'];
        $bill_detail->quantity = $value['qty'];
        $bill_detail->unit_price = $value['price'] / $value['qty'];
        $bill_detail->save();
    }

    Session::forget('cart');

    $wishlists = wishlists::where('id_user', Session::get('user')->id)->get();
    if (isset($wishlists)) {
        foreach ($wishlists as $element) {
            $element->delete();
        }
    }

    // Thêm mã chuyển hướng sau khi hoàn tất thanh toán (nếu cần)

    return redirect('trangchu');
}




}
