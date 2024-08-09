<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json([
            'data' => $customers
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|string|email|unique:customers|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::create($request->all());
        return response()->json([
            'data' => $customer
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json([
                'data' => $customer
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Customer not found with id ' . $id
            ], 404);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'position' => 'required'
        ], [
            'required' => "You need to fill :attribute,"
        ]);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 422);
        } else {
            try {
                $customer = Customer::findOrFail($id);
                $customer->update($request->all());
                return response()->json($customer, 200);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'Customer not found with id ' . $id
                ], 404);
            } catch (Exception $ex) {
                return response()->json([
                    'error' => 'Internal server error'
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return response()->json(["message" => "Successfully deleted"]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Customer not found with id ' . $id
            ], 404);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Internal server error'
            ], 500);
        }
    }
}
