<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Month;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    //Display employees by name, image and salary

    public function index()
    {

        $employee = DB::table('employees')
            ->select('id', 'first_name', 'image_url')->get();

        $employeeSalary = DB::table('months')
            ->select('employee_id', 'salary')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('months')
                    ->groupBy('employee_id');
            })
            ->get();

        $merged = $employee->merge($employeeSalary);
        $result = $merged->all();
        return response()->json(['message' => 'done',
            $result]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    //Display profile all employees

    public function show()
    {

        $employee = DB::table('employees')->get();

        return response()->json([
            'message' => 'Done',
            $employee
        ]);
    }

    //Display payment months by employee_id

    public function displayMonth($id)
    {
        $employee = Month::query()->where('employee_id', $id)->first();

        if ($employee == null) {
            return response()->json(['message' => 'Invalid ID'], 422);
        }

        $months = DB::table('months')
            ->select('month')
            ->where('employee_id', '=', $id)
            ->get();

        return response()->json([
            'message' => 'Done',
            'months' => $months
        ]);
    }

    //Calculate count of employee

    public function countOfEmployee()
    {
        $employees = DB::table('employees')->count();
        return response()->json([
            'The Number Of Employee is ' => $employees
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    //Store employee

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:employees,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $request->validate([
            'pharmacist_id' => 'required',
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'birth_date' => ['required'],
            'email' => 'required|email',
            'CV_file' => 'required|file',
            'phone_num' => 'required|numeric|min:10',
            'image_url' => 'required|file',//'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        if ($request->hasFile('CV_file')) {
            $destination_path = 'public/files/CVs';
            $CV_file = $request->file('CV_file');
            $file_name = $CV_file->getClientOriginalName();
            $path = $request->file('CV_file')->storeAs($destination_path, $file_name);
            $CV = Storage::url($path);
        }

        if ($request->hasFile('image_url')) {
            $destination_path = 'public/files/images';
            $image_url = $request->file('image_url');
            $file_name = $image_url->getClientOriginalName();
            $path = $request->file('image_url')->storeAs($destination_path, $file_name);
            $image = Storage::url($path);
        }

        $employee = Employee::query()->create([
            'pharmacist_id' => $request->pharmacist_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'email' => $request->email,
            'CV_file' => $CV,
            'phone_num' => $request->phone_num,
            'work_start_date' => $request->work_start_date = Carbon::now(),
            'image_url' => $image,
        ]);

        return response()->json([
            'The Employee' => $employee,
            'message' => 'Employee added successfully'
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    // Update employee

    public function update(Request $request, $id)
    {

        $employee = Employee::query()->find($id);
        if ($employee == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $birth_date = $request->input('birth_date');
        $email = $request->input('email');
        $phone_num = $request->input('phone_num');

        if ($request->hasFile('CV_file')) {
            $destination_path = 'public/files/CVs';
            $CV_file = $request->file('CV_file');
            $file_name = $CV_file->getClientOriginalName();
            $path = $request->file('CV_file')->storeAs($destination_path, $file_name);
            $cv = Storage::url($path);
            $employee->CV_file = $cv;
        }
        if ($request->hasFile('image_url')) {
            $destination_path = 'public/files/images';
            $image_url = $request->file('image_url');
            $file_name = $image_url->getClientOriginalName();
            $path = $request->file('image_url')->storeAs($destination_path, $file_name);
            $image = Storage::url($path);
            $employee->image_url = $image;
        }

        if ($first_name) {
            $employee->first_name = $first_name;
        }

        if ($last_name) {
            $employee->last_name = $last_name;
        }

        if ($birth_date) {
            $employee->phone_num = $phone_num;
        }
        if ($email) {
            $employee->email = $email;
        }
        if ($phone_num) {
            $employee->phone_num = $phone_num;
        }

        $employee->save();
        return response()->json([
            'message' => 'Updated Successfully', $employee
        ]);


    }


    //Store month and salary for each employee
    public function addMonthSalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',//|exists:employee:id',
            'month' => 'required',
            'salary' => 'required|numeric',
        ]);
        $monthSalary = Month::query()->create([
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'salary' => $request->salary,
        ]);
        return response()->json([
            'Monthly salary' => $monthSalary,
            'message' => 'Done'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    // Delete employee from data base
    public function destroy($id)
    {
        if ($employee = Employee::query()->find($id)) {
            $employee->delete();
            return response()->json(['message: ' => 'deleted'], 200);
        } else {
            return response()->json(['message: ' => 'invalid ID'], 422);
        }
    }


}
