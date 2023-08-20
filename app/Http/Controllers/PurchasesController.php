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
        $details = DB::table('purchases__details')
            ->where('purchases__bill_id', $id)
            ->where(function ($query) {
                $query->whereNotNull('medicine_id')
                    ->orWhereNotNull('product_id');
            })
            ->leftJoin('medicines', 'purchases__details.medicine_id', '=', 'medicines.id')
            ->leftJoin('products', 'purchases__details.product_id', '=', 'products.id')
            ->select(
                'medicines.trade_name',
                DB::raw('nullif(medicines.image_url, "") as medicine_image_url'),
                'products.name as product_name',
                DB::raw('nullif(products.image_url, "") as product_image_url'),
                'purchases__details.amount',
                'purchases__details.unit_price',
                'purchases__details.total_price'
            )
            ->get();
        return response()->json([
            'The medicine or product for this purchases bill' => $details,
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

    //check medicine or product is exist or not
    public function checkBarcode($barcode)
    {
        $medicine = Medicine::where('barcode', $barcode)->first();
        $product = Product::where('barcode', $barcode)->first();
        $response = [];
        if ($medicine) {
            $response = Medicine::query()
                ->select('id', 'trade_name as product', 'image_url')
                ->get()
                ->map(function ($item) {
                    return [
                        'medicine_id' => $item->id,
                        'product_id' => null,
                        'name' => $item->product,
                        'image_url' => $item->image_url,

                    ];
                });
        } elseif ($product) {
            $response = Product::query()
                ->select('id', 'name as product', 'image_url')
                ->get()
                ->map(function ($item) {
                    return [
                        'medicine_id' => null,
                        'product_id' => $item->id,
                        'name' => $item->product,
                        'image_url' => $item->image_url,
                    ];
                });
        }
        if (!empty($response)) {
            return response()->json(['message' => 'This product/medicine is exist', 'data' => $response], 200);
        } else {
            return response()->json(['message' => 'The medicine or product does not exist, please add!', 'data' => []], 404);
        }
    }
//--------------------------------------------------------------------------------------------------------

    //add details of purchases bill
    public function details(Request $request)
    {
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
            'medicine_id' => $request->medicine_id,
            'product_id' => $request->product_id,
            'amount' => $all_amount,
            'unit_price' => $unit_price,
            'total_price' => $total_price,
        ]);

        $medicine = Medicine::find($request->medicine_id);
        $product = Product::find($request->product_id);

        if ($medicine) {
            $medicine->amount += $all_amount;
            $medicine->net_price = $unit_price;
            $medicine->common_price = $medicine->net_price + ($medicine->net_price * 0.2);
            $medicine->save();
        }

        if ($product) {
            $product->amount += $all_amount;
            $product->piece_price = $unit_price;
            $product->common_price = $product->piece_price + ($product->piece_price * 0.25);
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
        return response()->json(['message' => 'The Purchases details ', 'details' => $details], 200);
    }

//--------------------------------------------------------------------------------------------------------


}
