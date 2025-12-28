<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\VirtualViewService;

class DashboardController extends Controller
{
public function index(Request $request)
{
    $year = $request->get('year', date('Y'));
    $month = $request->get('month', date('m'));
    
    $virtualViewService = new VirtualViewService();

    // Get monthly summary
    $monthlySummary = $virtualViewService->getMonthlySummary(null, null);
    
    // Get summary by type
    $summaryByType = $virtualViewService->getSummaryByType(auth()->id(), $year, $month);
    
    // Get type statistics with growth
    $typeStatistics = $virtualViewService->getTypeStatisticsWithGrowth(auth()->id(), $year);
    
    // Get type comparison over time
    $typeComparison = $virtualViewService->getTypeComparisonOverTime(auth()->id(), 'monthly', $year);
    
    // Get recent transactions
    $recentTransactions = $virtualViewService->getRecentTransactions();
    

    return view('dashboard', compact(
        'monthlySummary', 
        'summaryByType',
        'typeStatistics',
        'typeComparison',
        'recentTransactions',
        'year',
        'month'
    ));
}
}
