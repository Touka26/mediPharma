<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    //add product
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'barcode' => 'required',
            'name' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'combination',
            'caliber',
            'amount' => 'required|numeric|min:1',
            'piece_price' => 'required',
            'image_url' => 'required|file',
            'production_date' => 'required',
            'expiration_date' => 'required',
        ]);
        if ($request->hasFile('image_url')) {
            $destination_path = 'public/files/images';
            $image_url = $request->file('image_url');
            $file_name = $image_url->getClientOriginalName();
            $path = $request->file('image_url')->storeAs($destination_path, $file_name);
            $image = Storage::url($path);
        }
        $piecePrice = $request->input('piece_price');
        $commonPrice = $piecePrice + ($piecePrice * 0.25);
        $product = Product::query()->create([
            'category_id' => $request->category_id,
            'barcode' => $request->barcode,
            'name' => $request->name,
            'type' => $request->type,
            'combination' => $request->combination,
            'caliber' => $request->caliber,
            'amount' => $request->amount,
            'piece_price' => $piecePrice,
            'common_price' => $commonPrice,
            'image_url' => $image,
            'production_date' => $request->production_date,
            'expiration_date' => $request->expiration_date,
        ]);
        return response()->json([
            'message' => 'Done',
            'The product' => $product
        ]);
    }

//-----------------------------------------------------------------------------------------------------

    //display by Category
    public function show($id)
    {
        $category = Product::query()->where('category_id', $id)->first();

        if ($category == null) {
            return response()->json(['message' => 'Invalid ID'], 422);
        }
        $product = DB::table('products')
            ->where('category_id', '=', $id)
            ->select('id','name', 'amount', 'image_url','common_price')
            ->get();
        return response()->json(['message' => 'The product for this section',
            $product], 200);
    }

//-----------------------------------------------------------------------------------------------------

    // display product by product id
    public function showProduct($id)
    {
        $product = Product::query()->where('id', '=', $id)->first();

        if (!$product) {
            return response()->json(['message' => 'Invalid ID'], 404);
        }

        return response()->json(['message' => 'The product', 'product' => $product], 200);
    }

//-----------------------------------------------------------------------------------------------------

    //update product
    public function update(Request $request, $id)
    {
        $product = Product::query()->find($id);
        if ($product == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }
        $piecePrice = $request->input('piece\'s_price');
        $commonPrice = $piecePrice + ($piecePrice * 0.2);
        if ($piecePrice) {
            $product->{'piece_price'} = $piecePrice;
        }
        if ($commonPrice) {
            $product->common_price = $commonPrice;
        }

        $product->save();
        return response()->json([
            'message' => 'updated',
            'product' => $product
        ]);
    }
}
