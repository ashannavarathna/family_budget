<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
public function index()
    {
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $recentTransactions = Transaction::with(['user', 'category', 'transaction_type'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTransactions',
            'recentTransactions'
        ));
    }
}
