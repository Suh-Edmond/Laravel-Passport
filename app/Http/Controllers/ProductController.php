<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product = Product::paginate(5);
        return response()->json([$product, "status" => 206]);
    }


    public function store(Request $request)
    {
        $created = Product::create((array) $request->all());
        return response()->json(['data' => $created, "status" => "201", "msg" => "Successfully created Product"]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([$product, "status" => 200]);
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json(['data' => $product, 'msg' => 'Successfully updated', 'status' => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = Product::findOrFail($id)->delete();
        return response()->json(["data" => $deleted, "msg" => "Successfully deleted", "status" => 204]);
    }
}
