<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VirtualViewService
{
    /**
     * Get monthly summary for a user
     */
    public function getMonthlySummary($userId = null, $year = null)
    {
        $userId = $userId ?: auth()->id();
        $year = $year ?: date('Y');

        return DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                DB::raw("strftime('%Y', transactions.date) as year"),
                DB::raw("strftime('%m', transactions.date) as month"),
                DB::raw('COALESCE(SUM(CASE WHEN transaction_types.name = "収入" THEN transactions.amount ELSE 0 END), 0) as total_income'),
                DB::raw('COALESCE(SUM(CASE WHEN transaction_types.name = "支出" THEN transactions.amount ELSE 0 END), 0) as total_expense'),
                DB::raw('COALESCE(SUM(CASE WHEN transaction_types.name = "収入" THEN transactions.amount ELSE 0 END), 0) - 
                         COALESCE(SUM(CASE WHEN transaction_types.name = "支出" THEN transactions.amount ELSE 0 END), 0) as net_balance')
            )
            //->where('transactions.user_id', $userId)
            ->whereYear('transactions.date', $year)
            ->groupBy(DB::raw("strftime('%Y', transactions.date), strftime('%m', transactions.date)"))
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get summary by transaction type
     */
    public function getSummaryByType($userId = null, $year = null, $month = null)
    {
        $userId = $userId ?: auth()->id();

        $query = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                'transaction_types.name as type',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('AVG(transactions.amount) as average_amount'),
                DB::raw('MAX(transactions.amount) as max_amount'),
                DB::raw('MIN(transactions.amount) as min_amount')
            );
        //->where('transactions.user_id', $userId);

        if ($year) {
            $query->whereYear('transactions.date', $year);
        }
        if ($month) {
            $query->whereMonth('transactions.date', $month);
        }

        return $query->groupBy('transaction_types.name')
            ->orderBy('transaction_types.name')
            ->get();
    }

    /**
     * Get monthly summary by type
     */
    public function getMonthlySummaryByType($userId = null, $year = null)
    {
        $userId = $userId ?: auth()->id();
        $year = $year ?: date('Y');

        return DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                DB::raw("strftime('%Y', transactions.date) as year"),
                DB::raw("strftime('%m', transactions.date) as month"),
                'transaction_types.name as type',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('AVG(transactions.amount) as average_amount')
            )
            //->where('transactions.user_id', $userId)
            ->whereYear('transactions.date', $year)
            ->groupBy(DB::raw("strftime('%Y', transactions.date), strftime('%m', transactions.date), transaction_types.name"))
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('type')
            ->get();
    }

    /**
     * Get type summary with category breakdown
     */
    public function getTypeSummaryWithCategories($userId = null, $type = null, $year = null, $month = null)
    {
        $userId = $userId ?: auth()->id();

        $query = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                'transaction_types.name as type',
                'categories.name as category_name',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('ROUND(SUM(transactions.amount) * 100.0 / 
                         (SELECT SUM(t2.amount) FROM transactions t2
                          JOIN categories c2 ON t2.category_id = c2.id
                          JOIN transaction_types tt2 ON c2.transaction_type_id = tt2.id
                          WHERE t2.user_id = ? AND tt2.name = transaction_types.name'
                    . ($year ? " AND strftime('%Y', t2.date) = $year" : '')
                    . ($month ? " AND strftime('%m', t2.date) = $month" : '')
                    . '), 2) as percentage')
            )
            ->addBinding($userId, 'select');
        //->where('transactions.user_id', $userId);

        if ($type) {
            $query->where('transaction_types.name', $type);
        }
        if ($year) {
            $query->whereYear('transactions.date', $year);
        }
        if ($month) {
            $query->whereMonth('transactions.date', $month);
        }

        return $query->groupBy('transaction_types.name', 'categories.name')
            ->orderBy('type')
            ->orderBy('total_amount', 'desc')
            ->get();
    }

    /**
     * Get type comparison (monthly, quarterly, yearly)
     */
    public function getTypeComparisonOverTime($userId = null, $period = 'monthly', $year = null)
    {
        $userId = $userId ?: auth()->id();
        $year = $year ?: date('Y');

        $query = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id');

        if ($period === 'monthly') {
            $query->select(
                DB::raw("strftime('%Y', transactions.date) as year"),
                DB::raw("strftime('%m', transactions.date) as month"),
                'transaction_types.name as type',
                DB::raw('SUM(transactions.amount) as total_amount')
            )
                ->whereYear('transactions.date', $year)
                ->groupBy(DB::raw("strftime('%Y', transactions.date), strftime('%m', transactions.date), transaction_types.name"))
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('type');
        } elseif ($period === 'yearly') {
            $query->select(
                DB::raw("strftime('%Y', transactions.date) as year"),
                'transaction_types.name as type',
                DB::raw('SUM(transactions.amount) as total_amount')
            )
                ->groupBy(DB::raw("strftime('%Y', transactions.date), transaction_types.name"))
                ->orderBy('year', 'desc')
                ->orderBy('type');
        } elseif ($period === 'quarterly') {
            $query->select(
                DB::raw("strftime('%Y', transactions.date) as year"),
                DB::raw("((cast(strftime('%m', transactions.date) as integer)-1)/3+1) as quarter"),
                'transaction_types.name as type',
                DB::raw('SUM(transactions.amount) as total_amount')
            )
                ->whereYear('transactions.date', $year)
                ->groupBy(DB::raw("strftime('%Y', transactions.date), ((cast(strftime('%m', transactions.date) as integer)-1)/3+1), transaction_types.name"))
                ->orderBy('year')
                ->orderBy('quarter')
                ->orderBy('type');
        }

        return $query->get();
    }

    /**
     * Get type statistics with growth comparison
     */
    public function getTypeStatisticsWithGrowth($userId = null, $year = null)
    {
        $userId = $userId ?: auth()->id();
        $year = $year ?: date('Y');
        $previousYear = $year - 1;

        $currentYearData = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                'transaction_types.name as type',
                DB::raw('SUM(transactions.amount) as current_year_total'),
                DB::raw('COUNT(transactions.id) as current_year_count')
            )
            //->where('transactions.user_id', $userId)
            ->whereYear('transactions.date', $year)
            ->groupBy('transaction_types.name')
            ->get()
            ->keyBy('type');

        $previousYearData = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                'transaction_types.name as type',
                DB::raw('SUM(transactions.amount) as previous_year_total'),
                DB::raw('COUNT(transactions.id) as previous_year_count')
            )
            //->where('transactions.user_id', $userId)
            ->whereYear('transactions.date', $previousYear)
            ->groupBy('transaction_types.name')
            ->get()
            ->keyBy('type');

        $result = [];
        $types = ['収入', '支出'];

        foreach ($types as $type) {
            $current = $currentYearData->get($type);
            $previous = $previousYearData->get($type);

            $currentTotal = $current ? $current->current_year_total : 0;
            $previousTotal = $previous ? $previous->previous_year_total : 0;

            $growth = $previousTotal != 0 ?
                (($currentTotal - $previousTotal) / $previousTotal) * 100 :
                ($currentTotal > 0 ? 100 : 0);

            $result[] = [
                'type' => $type,
                'current_year_total' => $currentTotal,
                'previous_year_total' => $previousTotal,
                'growth_percentage' => round($growth, 2),
                'current_year_count' => $current ? $current->current_year_count : 0,
                'previous_year_count' => $previous ? $previous->previous_year_count : 0,
            ];
        }

        return collect($result);
    }

    /**
     * Get category summary
     */
    public function getCategorySummary($userId = null, $startDate = null, $endDate = null, $transactionTypeId = null, $categoryId = null)
    {
        // Keep it as null if no ID is passed, which indicates "all users" for an admin report. 
        //$userId = $userId ?: auth()->id();

        $query = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                'categories.name as category_name',
                'transaction_types.name as type',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.amount) as total_amount'),
                DB::raw('AVG(transactions.amount) as average_amount')
            );

        if ($userId) {
            $query->where('transactions.user_id', $userId);
        }
        if ($startDate) {
            $query->where('transactions.date', '>=', $startDate);
        } else {
            // set to current month start date
            //Carbon::now()->startOfMonth()->toDateString();
            $query->where('date', '>=', Carbon::now()->startOfMonth()->toDateString());
        }
        if ($endDate) {
            $query->where('transactions.date', '<=', $endDate);
        } else {
            // set to curent month end date
            //Carbon::now()->endOfMonth()->toDateString();
            $query->where('date', '<=', Carbon::now()->endOfMonth()->toDateString());
        }
        if ($transactionTypeId) {
            $query->where('transactions.transaction_type_id', $transactionTypeId);
        }
        if ($categoryId) {
            $query->where('transactions.category_id', $categoryId);
        }

        return $query->groupBy('categories.name', 'transaction_types.name')->get();
    }

    /**
     * Get yearly summary
     */
    public function getYearlySummary($userId = null)
    {
        $userId = $userId ?: auth()->id();

        return DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                DB::raw("strftime('%Y', transactions.date) as year"),
                DB::raw('COALESCE(SUM(CASE WHEN transaction_types.name = "収入" THEN transactions.amount ELSE 0 END), 0) as total_income'),
                DB::raw('COALESCE(SUM(CASE WHEN transaction_types.name = "支出" THEN transactions.amount ELSE 0 END), 0) as total_expense'),
                DB::raw('COALESCE(SUM(CASE WHEN transaction_types.name = "収入" THEN transactions.amount ELSE 0 END), 0) - 
                         COALESCE(SUM(CASE WHEN transaction_types.name = "支出" THEN transactions.amount ELSE 0 END), 0) as net_balance')
            )
            //->where('transactions.user_id', $userId)
            ->groupBy(DB::raw("strftime('%Y', transactions.date)"))
            ->orderBy('year', 'desc')
            ->get();
    }

    /**
     * Get recent transactions
     */
    public function getRecentTransactions($userId = null, $limit = 5)
    {
        $userId = $userId ?: auth()->id();

        return DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                'transactions.*',
                'categories.name as category_name',
                'transaction_types.name as type_name'
            )
            //->where('transactions.user_id', $userId)
            ->orderBy('transactions.date', 'desc')
            ->orderBy('transactions.created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get transactions by date range
     */
    public function getTransactionsByDateRange($userId = null, $startDate, $endDate)
    {
        $userId = $userId ?: auth()->id();

        return DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                'transactions.*',
                'categories.name as category_name',
                'transaction_types.name as type_name'
            )
            //->where('transactions.user_id', $userId)
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->orderBy('transactions.date', 'desc')
            ->get();
    }

    public function getCategorySummaryForCurrentMonth(
        $userId = null,
        $startDate = null,
        $endDate = null,
        $transactionTypeId = null,
        $categoryId = null

    ) {

        $query = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(transactions.amount) as total')
            );

        if ($userId) {
            $query->where('transactions.user_id', $userId);
        }

        $query->whereBetween('transactions.date', [
            $startDate ?? Carbon::now()->startOfMonth()->toDateString(),
            $endDate ?? Carbon::now()->endOfMonth()->toDateString(),
        ]);

        if ($transactionTypeId) {
            $query->where('categories.transaction_type_id', $transactionTypeId);
        }

        if ($categoryId) {
            $query->where('transactions.category_id', $categoryId);
        }

        return $query
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

    }
}
