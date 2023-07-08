<?php

namespace App\Http\Controllers;

use App\Models\Manufacture;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    //Display all companies
    public function index()
    {
        $manufacture = DB::table('manufactures')
            ->select('company_name')
            ->orderBy('company_name')->get();
        return response()->json([
            'The manufacture' => $manufacture
        ], 200);
    }

    //search by barcode
    public function searchByBarcode($barcode)
    {
        $medicine = DB::table('medicines')->where('barcode', '=', $barcode)->first();
        if ($medicine == null) {
            return response()->json(['message' => 'The medicine is not exist'], 404);
        } else

            return response()->json(['message' => 'The Medicine ', $medicine], 200);
    }


    //search by trade name
    public function searchByTradeName($trade)
    {
        $medicine = DB::table('medicines')->where('trade_name', '=', $trade)->first();
        if ($medicine == null) {
            return response()->json(['message' => 'The medicine is not exist'], 404);
        } else

            return response()->json(['message' => 'The Medicine ', $medicine], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    //add medicine
    public function store(Request $request)
    {
        $request->validate([
            'manufacture_id' => 'required',
            'barcode' => 'required',
            'trade_name' => 'required|max:50',
            'combination' => 'required|max:50',
            'caliber' => 'required|max:50',
            'type' => 'required|max:50',
            'pharmaceutical_form' => 'required|max:50',
            'net_price' => 'required',
            'amount' => 'required',
            'statement' => 'required',
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
        $netPrice = $request->input('net_price');
        $commonPrice = $netPrice + ($netPrice * 0.2);

        $medicine = Medicine::query()->create([
            'manufacture_id' => $request->manufacture_id,
            'barcode' => $request->barcode,
            'trade_name' => $request->trade_name,
            'combination' => $request->combination,
            'caliber' => $request->caliber,
            'type' => $request->type,
            'pharmaceutical_form' => $request->pharmaceutical_form,
            'net_price' => $netPrice,
            'common_price' => $commonPrice,
            'amount' => $request->amount,
            'statement' => $request->statement,
            'image_url' => $image,
            'production_date' => $request->production_date,
            'expiration_date' => $request->expiration_date,
        ]);

        return response()->json([
            'message' => 'done',
            'The medicine ' => $medicine
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
        $manufacture = Medicine::query()->where('manufacture_id', $id)->first();

        if ($manufacture == null) {
            return response()->json(['message' => 'Invalid ID'], 422);
        }
        $medicine = DB::table('medicines')
            ->where('manufacture_id', '=', $id)
            ->select('trade_name', 'amount', 'image_url')
            ->get();
        return response()->json(['message' => 'The medicine for this manufacture',
            $medicine], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    //update medicine
    public function update(Request $request, $id)
    {
        $medicine = Medicine::query()->find($id);
        if ($medicine == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }
        $netPrice = $request->input('net_price');
        $commonPrice = $netPrice + ($netPrice * 0.2);
        if ($netPrice) {
            $medicine->net_price = $netPrice;
        }
        if ($commonPrice) {
            $medicine->common_price = $commonPrice;
        }

        $medicine->save();
        return response()->json([
            'message' => 'updated',
            'medicine' => $medicine
        ]);
    }

}
