<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TransactionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * 
     */
    public function index()
    {
        $transactionTypes = TransactionType::all();
        return view ('admin.transaction_type.index', compact('transactionTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * 
     */
    public function create()
    {
        return view('admin.transaction_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function store(Request $request)
    {
    $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:transaction_types,name'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            TransactionType::create($request->all());
            
            return redirect()->route('admin.transaction_types.index')
                ->with('success', 'Transaction type created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating transaction type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\TransactionType  $transactionType
     * 
     */
    public function edit(TransactionType $transactionType)
    {
        return view('admin.transaction_type.edit', compact('transactionType'));
    }    

    /**
     * Display the specified resource.
     *
     * @param  \App\models\TransactionType  $transactionType
     * 
     */
    public function show(TransactionType $transactionType)
    {
        return view('admin.transaction_type.edit', compact('transactionType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\TransactionType  $transactionType
     * 
     */
    public function update(Request $request, TransactionType $transactionType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:transaction_types,name,' . $transactionType->id
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $transactionType->update($request->all());
            
            return redirect()->route('admin.transaction-types.index')
                ->with('success', 'Transaction type updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating transaction type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\TransactionType  $transactionType
     * 
     */
    public function destroy(TransactionType $transactionType)
    {
        try {
            // Check if transaction type has related categories or transactions
            if ($transactionType->categories()->count() > 0 || $transactionType->transactions()->count() > 0) {
                return redirect()->route('admin.transaction-types.index')
                    ->with('error', 'Cannot delete transaction type. It has related categories or transactions.');
            }

            $transactionType->delete();
            
            return redirect()->route('admin.transaction-types.index')
                ->with('success', 'Transaction type deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.transaction-types.index')
                ->with('error', 'Error deleting transaction type: ' . $e->getMessage());
        }
    }
    
}
