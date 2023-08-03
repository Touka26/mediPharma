<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Pharmacist;
use App\Models\Product;
use App\Models\Purchases_Bill;
use App\Models\Purchases_Detail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchasesController extends Controller
{


    //display all purchases by image_url and today_date
    public function index()
    {
        $purchases = Purchases_Bill::orderBy('today_date')
            ->withSum('details', 'total_price')
            ->get();

        return response()->json(['message' => 'All purchases bills', 'purchases' => $purchases]);
    }

//--------------------------------------------------------------------------------------------------------

    //display details of specific purchases bill
    public function detailsPurchases($id)
    {
        $details = Purchases_Bill::query()->find($id);
        if ($details == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }
        $details = DB::table('purchases__details')
            ->where('purchases__bill_id', $id)
            ->where(function ($query) {
                $query->whereNotNull('medicine_id')
                    ->orWhereNotNull('product_id');
            })
            ->leftJoin('medicines', 'purchases__details.medicine_id', '=', 'medicines.id')
            ->leftJoin('products', 'purchases__details.product_id', '=', 'products.id')
            ->select( 'medicines.trade_name', DB::raw('COALESCE(medicines.image_url, "") as medicine_image_url'),
                'products.name as product_name', DB::raw('COALESCE(products.image_url, "") as product_image_url'),
                'purchases__details.amount', 'purchases__details.unit_price', 'purchases__details.total_price',
               )
            ->get();

//        // Filter out entries where both product_name and product_image_url are null
//        $filteredDetails = $details->filter(function ($detail) {
//            return (
//                (!is_null($detail->product_name) && trim($detail->product_name) !== 'null') ||
//                (!is_null($detail->product_image_url) && trim($detail->product_image_url) !== '') ||
//                (!is_null($detail->trade_name) && trim($detail->trade_name) !== 'null') ||
//                (!is_null($detail->medicine_image_url) && trim($detail->medicine_image_url) !== '')
//            );
//        });

//        if ($filteredDetails->isEmpty()) {
//            return response()->json(['message' => 'No valid medicine or product found for this purchases bill'], 404);
//        }

//        $total_price = DB::table('purchases__details')
//            ->where('purchases__bill_id', $id)
//            ->sum('total_price');

        return response()->json([
            'The medicine or product for this purchases bill' => $details,
//            'total_price' => $total_price
        ], 200);
    }

//--------------------------------------------------------------------------------------------------------

    //Search by purchases date
    public function searchByDate($date)
    {
        $purchases = Purchases_Bill::query()
            ->where('today_date', $date)
            ->get();

        if ($purchases->isEmpty()) {
            return response()->json(['message' => 'No purchases bills found for this date'], 404);
        }

        return response()->json(['message' => 'Purchases bills for this date', 'purchases' => $purchases], 200);
    }

//--------------------------------------------------------------------------------------------------------

    //add purchases bill
    public function storePurchases(Request $request)
    {
        $request->validate([
            'pharmacist_id' => 'required|exists:pharmacists,id',
            'storehouse_name' => 'required|max:50',
            'statement' => 'required|max:50',
            'image_url' => 'required|file'
        ]);
        if ($request->hasFile('image_url')) {
            $destination_path = 'public/files/images';
            $image_url = $request->file('image_url');
            $file_name = $image_url->getClientOriginalName();
            $path = $request->file('image_url')->storeAs($destination_path, $file_name);
            $image = Storage::url($path);
        }
        $purchases = Purchases_Bill::query()->create([
            'pharmacist_id' => $request->pharmacist_id,
            'storehouse_name' => $request->storehouse_name,
            'statement' => $request->statement,
            'image_url' => $image,
            'today_date' => $request->today_date = Carbon::now()->format('Y-m-d')
        ]);
        return response()->json([
            'The bill ' => $purchases,
            'message' => 'Done'], 200);
    }

//--------------------------------------------------------------------------------------------------------

    //add details of purchases bill
    public function details(Request $request, $barcode)
    {
        $medicine = Medicine::where('barcode', $barcode)->first();
        $product = Product::where('barcode', $barcode)->first();

        if (!$medicine && !$product) {
            return response()->json(['message' => 'The medicine or product does not exist, please add it.'], 404);
        }

        $request->validate([
            'purchases__bill_id' => 'required',
            'amount' => 'required',
            'unit_price' => 'required',
        ]);

        $all_amount = $request->input('amount');
        $unit_price = $request->input('unit_price');
        $total_price = $all_amount * $unit_price;

        $details = Purchases_Detail::create([
            'purchases__bill_id' => $request->purchases__bill_id,
            'medicine_id' => $medicine ? $medicine->id : null,
            'product_id' => $product ? $product->id : null,
            'amount' => $all_amount,
            'unit_price' => $unit_price,
            'total_price' => $total_price,
        ]);

        if ($medicine || $product) {
            if ($medicine) {
                $medicine->amount += $details->amount;
                $medicine->save();
            }

            if ($product) {
                $product->amount += $details->amount;
                $product->save();
            }

            // Update pharmacist's financial fund
            $purchase = Purchases_Bill::where('id', $request->purchases__bill_id)
                ->select('pharmacist_id')->first();

            if ($purchase) {
                $pharmacistId = $purchase->pharmacist_id;
                $pharmacist = Pharmacist::find($pharmacistId);
                if ($pharmacist) {
                    $pharmacist->financial_fund -= $total_price;
                    $pharmacist->save();
                }
            }
        } else {
            return response()->json(['message' => 'The medicine or product does not exist'], 404);
        }

        // Build selected details for response
        $selectedDetails = [];
        if ($medicine) {
            $selectedDetails['medicine_trade_name'] = $medicine->trade_name;
            $selectedDetails['medicine_image_url'] = $medicine->image_url;
        }
        if ($product) {
            $selectedDetails['product_name'] = $product->name;
            $selectedDetails['product_image_url'] = $product->image_url;
        }

        return response()->json([
            'message' => 'Purchase details added successfully',
            'details' => $selectedDetails,
            'purchases_detail' => $details
        ], 200);
    }

//--------------------------------------------------------------------------------------------------------


}
