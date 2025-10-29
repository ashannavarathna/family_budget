<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\TransactionType  $transactionType
     * 
     */
    public function show(TransactionType $transactionType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\TransactionType  $transactionType
     * 
     */
    public function edit(TransactionType $transactionType)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\TransactionType  $transactionType
     * 
     */
    public function destroy(TransactionType $transactionType)
    {
        //
    }
}
