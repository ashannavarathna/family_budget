<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\TransactionType;
use App\Models\User;
use App\Services\VirtualViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index(Request $request)
    {

        $query = Transaction::with(['category', 'transaction_type', 'user']);
            //->where('created_by', Auth::id());

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->whereHas('transaction_type', function($q) use ($request) {
                $q->where('name', $request->type);
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('date', '>=', $request->start_date);
        }else{
            // set to current month start date
            //Carbon::now()->startOfMonth()->toDateString();
            $query->where('date', '>=', VirtualViewService::getAccountPeriod(0)['periodStart']->startOfDay()->toDateString());
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('date', '<=', $request->end_date);
        }else{
            // set to curent month end date
            //Carbon::now()->endOfMonth()->toDateString();
            $query->where('date', '<=', VirtualViewService::getAccountPeriod(0)['periodEnd']->startOfDay()->toDateString());
        }

        //$transactions = $query->latest()->paginate(10);
        //listing by desc with date column

        

        $transactions = $query->orderBy('date', 'desc')->paginate(10)->appends(request()->query());

        $categories = Category::with('transaction_type')->get();
        $transactionTypes = TransactionType::all();

        return view('transactions.index', compact('transactions', 'categories', 'transactionTypes'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        $categories = Category::with('transaction_type')->get();
        $transactionTypes = TransactionType::all();
        $accounts = User::all();
        
        return view('transactions.create', compact('categories', 'transactionTypes', 'accounts'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'account_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'party' => 'required|string|max:255',
            'receipt_number' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Verify category belongs to selected transaction type
        $category = Category::find($validated['category_id']);
        if ($category->transaction_type_id != $validated['transaction_type_id']) {
            return back()->withErrors(['category_id' => 'Selected category does not belong to the chosen transaction type.'])->withInput();
        }

        Transaction::create([
            'user_id' => $validated['account_id'],
            'category_id' => $validated['category_id'],
            'transaction_type_id' => $validated['transaction_type_id'],
            'party' => $validated['party'],
            'receipt_number' => $validated['receipt_number'],
            'created_by' => Auth::id(),
            'date' => $validated['date'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'month' => date('n', strtotime($validated['date'])),
            'year' => date('Y', strtotime($validated['date'])),
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        // Authorization - user can only view their own transactions
        //if ($transaction->created_by != Auth::id() && !Auth::user()->isAdmin()) {
        //    abort(403, 'Unauthorized action.');
        //}

        $transaction->load(['category', 'transaction_type', 'user']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaction $transaction)
    {
        // Authorization - user can only edit their own transactions
        if ($transaction->created_by != Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::with('transaction_type')->get();
        $transactionTypes = TransactionType::all();
        $accounts = User::all();

        return view('transactions.edit', compact('transaction', 'categories', 'transactionTypes', 'accounts'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Authorization - user can only update their own transactions
        if ($transaction->created_by != Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'account_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'party' => 'required|string|max:255',
            'receipt_number' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Verify category belongs to selected transaction type
        $category = Category::find($validated['category_id']);
        if ($category->transaction_type_id != $validated['transaction_type_id']) {
            return back()->withErrors(['category_id' => 'Selected category does not belong to the chosen transaction type.'])->withInput();
        }

        $transaction->update([
            'user_id' => $validated['account_id'],
            'category_id' => $validated['category_id'],
            'transaction_type_id' => $validated['transaction_type_id'],
            'party' => $validated['party'],
            'receipt_number' => $validated['receipt_number'],
            'date' => $validated['date'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'month' => date('n', strtotime($validated['date'])),
            'year' => date('Y', strtotime($validated['date'])),
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Authorization - user can only delete their own transactions
        if ($transaction->created_by != Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Get categories by transaction type (for AJAX)
     */
    public function getCategoriesByType($typeId)
    {
        $categories = Category::where('transaction_type_id', $typeId)->get();
        return response()->json($categories);
    }
}