<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //search by barcode
    public function searchByBarcode($barcode)
    {
        $product = DB::table('products')->where('barcode', '=', $barcode)->first();
        if ($product == null) {
            return response()->json(['message' => 'The product is not exist'], 404);
        } else

            return response()->json(['message' => 'The Product ', $product], 200);
    }

    //search by name
    public function searchByName($name)
    {
        $product = DB::table('products')->where('name', '=', $name)->first();
        if ($product == null) {
            return response()->json(['message' => 'The product is not exist'], 404);
        } else

            return response()->json(['message' => 'The Product ', $product], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    //add product
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'barcode' => 'required',
            'name' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'combination',
            'caliber',
            'amount' => 'required|integer',
            'piece\'s_price' => 'required',
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
        $piecePrice = $request->input('piece\'s_price');
        $commonPrice = $piecePrice + ($piecePrice * 0.25);
        $product = Product::query()->create([
            'category_id' => $request->category_id,
            'barcode' => $request->barcode,
            'name' => $request->name,
            'type' => $request->type,
            'combination' => $request->combination,
            'caliber' => $request->caliber,
            'amount' => $request->amount,
            'piece\'s_price' => $piecePrice,
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    //display by company medicine
    public function show($id)
    {
        $category = Product::query()->where('category_id', $id)->first();

        if ($category == null) {
            return response()->json(['message' => 'Invalid ID'], 422);
        }
        $product = DB::table('products')
            ->where('category_id', '=', $id)
            ->select('name', 'amount', 'image_url')
            ->get();
        return response()->json(['message' => 'The product for this section',
            $product], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
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
            $product->{'piece\'s_price'} = $piecePrice;
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
