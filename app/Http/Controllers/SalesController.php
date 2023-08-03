<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Pharmacist;
use App\Models\Product;
use App\Models\Sales_Bill;
use App\Models\Sales_Detail;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SalesController extends Controller
{

    //add sales bill
    public function storeSales(Request $request)
    {
        $request->validate([
            'pharmacist_id' => 'required|exists:pharmacists,id',
        ]);

        $sales = Sales_Bill::query()->create([
            'pharmacist_id' => $request->pharmacist_id,
            'today_date' => $request->today_date = Carbon::today()->format('Y-m-d')
        ]);
        return response()->json(['message' => 'added', $sales], 200);
    }

//--------------------------------------------------------------------------------------

    //display all sales bill by date and their ids
    public function showAllSales()
    {
        $sales = Sales_Bill::query()->select('id', 'today_date')
            ->orderBy('today_date')
            ->get();
        return response()->json(['message' => 'all sales bill', $sales], 200);
    }

//--------------------------------------------------------------------------------------

    //display all sales bill details by id
    public function showSalesDetails($id)
    {
        $salesDetails = Sales_Detail::query()
            ->where('sales__bill_id', $id)
            ->get();

        if ($salesDetails->isEmpty()) {
            return response()->json(['message' => 'No sales details found for this sales bill'], 404);
        }

        return response()->json(['message' => 'All sales details for this sales bill', 'salesDetails' => $salesDetails], 200);

    }

//--------------------------------------------------------------------------------------


    // store the medicine or product in the bug
    public function storeBug(Request $request)
    {
        $request->validate([
            'sales__bill_id' => 'required|exists:sales__bills,id',
            'medicine_id' => 'exists:medicines,id',
            'product_id' => 'exists:products,id',
            'name' => 'required|max:50',
            'quantity_sold' => 'required',
            'unit_price' => 'required',
            'image_url' => 'required|file',
        ]);
        if ($request->hasFile('image_url')) {
            $destination_path = 'public/files/images';
            $image_url = $request->file('image_url');
            $file_name = $image_url->getClientOriginalName();
            $path = $request->file('image_url')->storeAs($destination_path, $file_name);
            $image = Storage::url($path);
        }
        $quantity_sold = $request->input('quantity_sold');
        $unit_price = $request->input('unit_price');
        $medicine_id = $request->input('medicine_id');
        $product_id = $request->input('product_id');
        $total_price = $quantity_sold * $unit_price;

        $storeBug = Store::query()->create([
            'sales__bill_id' => $request->sales__bill_id,
            'medicine_id' => $medicine_id,
            'product_id' => $product_id,
            'name' => $request->name,
            'quantity_sold' => $quantity_sold,
            'unit_price' => $unit_price,
            'total_price' => $total_price,
            'image_url' => $image,
        ]);

        if ($medicine_id && $product_id) {
            // Handle the case when both medicine and product are provided
            return response()->json(['message' => 'Please provide either medicine_id or product_id, not both'], 400);
        } elseif ($medicine_id) {
            // Handle when only medicine_id is provided
            $medicine = Medicine::query()->find($medicine_id);
            if (!$medicine) {
                return response()->json(['message' => 'The medicine does not exist'], 404);
            }
            $medicine->amount -= $quantity_sold;
            $medicine->save();
        } elseif ($product_id) {
            // Handle when only product_id is provided
            $product = Product::query()->find($product_id);
            if (!$product) {
                return response()->json(['message' => 'The product does not exist'], 404);
            }
            $product->amount -= $quantity_sold;
            $product->save();
        } else {
            // Handle the case when both medicine_id and product_id are null
            return response()->json(['message' => 'Please provide either medicine_id or product_id'], 400);
        }

        return response()->json([
            'message' => 'the store bug',
            'storeBug' => $storeBug
        ], 200);

    }

//--------------------------------------------------------------------------------------

    //check medicine if forbidden or not
    public function checkMedicine(Request $request, $id)
    {
        $forbidden = Store::query()
            ->join('medicines', 'stores.medicine_id', '=', 'medicines.id')
            ->where('stores.medicine_id', '=', $id)
            ->select('medicines.statement')
            ->first();

        if ($forbidden && $forbidden->statement == 1) {
            $request->validate([
                'prescription_url' => 'required',
                'id_number' => 'required',
            ]);

            if ($request->hasFile('prescription_url')) {
                $destination_path = 'public/files/images';
                $prescription_url = $request->file('prescription_url');
                $file_name = $prescription_url->getClientOriginalName();
                $path = $request->file('prescription_url')->storeAs($destination_path, $file_name);
                $image = Storage::url($path);
            }

            $medicine = Medicine::query()->where('id', $id)->update([
                'prescription_url' => $image,
                'id_number' => $request->id_number,
            ]);

            return response()->json([
                'message' => 'added',
                'medicine' => $medicine
            ], 200);
        } elseif ($forbidden && $forbidden->statement != 1) {
            return response()->json(['message' => 'is not forbidden'], 200);
        } else {
            return response()->json(['message' => 'invalid id'], 422);
        }
    }

//--------------------------------------------------------------------------------------

    //show store bug
    public function showBug()
    {
        $store = Store::query()->get();
        return response()->json(['message' => 'all bug', $store], 200);
    }

//--------------------------------------------------------------------------------------


    //delete medicine or product from store
    public function destroy($id)
    {
        $store = Store::find($id);

        if (!$store) {
            return response()->json(['message' => 'Invalid store_id'], 422);
        }

        if ($store->medicine_id) {
            $medicine = Medicine::find($store->medicine_id);
            if ($medicine) {
                // Validate that the quantity sold is greater than zero
                if ($store->quantity_sold > 0) {
                    $medicine->amount += $store->quantity_sold;
                    $medicine->save();
                } else {
                    return response()->json(['message' => 'Invalid quantity sold'], 422);
                }
            }
        } elseif ($store->product_id) {
            $product = Product::find($store->product_id);
            if ($product) {
                // Validate that the quantity sold is greater than zero
                if ($store->quantity_sold > 0) {
                    $product->amount += $store->quantity_sold;
                    $product->save();
                } else {
                    return response()->json(['message' => 'Invalid quantity sold'], 422);
                }
            }
        }

        $store->delete();

        return response()->json(['message' => 'Row deleted successfully'], 200);
    }

//--------------------------------------------------------------------------------------

    //sale confirmation
    public function confirmation()
    {
        $stores = Store::all();
        foreach ($stores as $sale) {
            Sales_Detail::create([
                'sales__bill_id' => $sale->sales__bill_id,
                'medicine_id' => $sale->medicine_id,
                'product_id' => $sale->product_id,
                'name' => $sale->name,
                'quantity_sold' => $sale->quantity_sold,
                'unit_price' => $sale->unit_price,
                'total_price' => $sale->total_price,
                'image_url' => $sale->image_url,
            ]);
        }
        // Calculate total transferred amount
        $totalTransferredAmount = $stores->sum('total_price');

        // Update pharmacist's financial fund
        $salesBill = Sales_Bill::query()
            ->where('id', '=', $sale->sales__bill_id)
            ->select('pharmacist_id')->first();
        if ($salesBill) {
            $pharmacistId = $salesBill->pharmacist_id;
            $pharmacist = Pharmacist::query()->where('id', $pharmacistId)->first();
            if ($pharmacist) {
                $pharmacist->financial_fund += $totalTransferredAmount;
                $pharmacist->save();
            }
        }
        $storeIds = $stores->pluck('id')->toArray(); // Convert IDs to array
        Store::destroy($storeIds); // Pass the array of IDs
        return response()->json([
            'message' => 'Rows transferred successfully and deleted from stores',
            $totalTransferredAmount
        ], 200);
    }

//--------------------------------------------------------------------------------------

    public function diagram()
    {
        $diagram = Sales_Bill::query()
            ->selectRaw("DATE_FORMAT(today_date, '%a') as day_name")
            ->selectRaw('SUM(sales__details.total_price) as total_sales')
            ->join('sales__details', 'sales__bills.id', '=', 'sales__details.sales__bill_id')
            ->groupBy('day_name')
            ->orderByRaw("FIELD(day_name,'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat')")
            ->get();

        return response()->json(['diagram_info' => $diagram], 200);
    }

//--------------------------------------------------------------------------------------

    //search sales by date
    public function searchByDate($date)
    {
        $sales = Sales_Bill::query()
            ->where('today_date', $date)
            ->get();

        if ($sales->isEmpty()) {
            return response()->json(['message' => 'No sales bills found for this date'], 404);
        }

        return response()->json(['message' => 'Sales bills for this date', 'Sales' => $sales], 200);
    }

//--------------------------------------------------------------------------------------


}


