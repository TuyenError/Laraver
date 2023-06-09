<?php

namespace App\Http\Controllers;

use App\Models\producttiki1;

use Illuminate\Http\Request;

class ProducttikiController extends Controller
{
    public function getProducts()
    {
        $product = producttiki1::all();
        return response()->json($product);
    }
    public function getOneProduct($id)
    {
        $product = producttiki1::find($id);
        return response()->json($product);
    }
    public function addProduct(Request $request)
    {
        $product = new producttiki1();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->image = $request->input('image');
        $product->sale = $request->input('sale');
        $product->description = $request->input('description');
        $product->sold = $request->input('sold');
        $product->save();
        return $product;
    }



    public function uploadImage(Request $request)
    {
    // process image
        if ($request->hasFile('uploadImage')) {
        $file = $request->file('uploadImage');
        $fileName = $file->getClientOriginalName();

        $file->move('source/image/product', $fileName);

        return response()->json(["message" => "ok"]);
        } else return response()->json(["message" => "false"]);
    }

}


