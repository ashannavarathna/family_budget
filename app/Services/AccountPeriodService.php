<?php
namespace App\Services;

use App\Models\AccountPeriod;
use Illuminate\Support\Facades\DB;

class AccountPeriodService{
    public function calculatePeriod(AccountPeriod $period): array{
        //safety check
        if($period->is_closed){
            throw new \Exception('Cannot calculate a closed period');
        }

        // =========================
        // Totals
        // =========================
        $totals = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('transaction_types', 'categories.transaction_type_id', '=', 'transaction_types.id')
            //->where('transactions.user_id', $period->user_id)
            ->whereBetween('transactions.date', [
                $period->period_start,
                $period->period_end
            ])
            ->select(
                'categories.name as category',
                'transaction_types.name as type',
                DB::raw('SUM(transactions.amount) as total')
            )
            ->groupBy('transaction_types.name')
            ->pluck('total', 'type');

        $totalIncome  = (float) ($totals['収入'] ?? 0);
        $totalExpense = (float) ($totals['支出'] ?? 0);

        // =========================
        // Closing Balance
        // =========================
        $closingBalance =
            $period->opening_balance
            + $totalIncome
            - $totalExpense;

        return [
            'income'  => $totalIncome,
            'expense' => $totalExpense,
            'opening_balance' => (float) $period->opening_balance,
            'closing_balance' => $closingBalance,
        ];
    }

    public function closePeriod(AccountPeriod $period): void{
        if ($period->is_closed) {
            throw new \Exception('Period already closed');
        }

        $summary = $this->calculatePeriod($period);

        $period->update([
            'closing_balance' => $summary['closing_balance'],
            'is_closed' => true,
        ]);
    }

    public function createNextPeriod(AccountPeriod $currentPeriod): AccountPeriod
    {
        if (!$currentPeriod->is_closed) {
            throw new \Exception('Close current period first');
        }

        return AccountPeriod::create([
            'user_id' => $currentPeriod->user_id,
            'period_start' => $currentPeriod->period_end->addDay(),
            'period_end' => $currentPeriod->period_end->addMonth(),
            'opening_balance' => $currentPeriod->closing_balance,
            'is_closed' => false,
        ]);
    }
    
    public function isClosable(AccountPeriod $period): bool
    {
        return now()->greaterThanOrEqualTo($period->period_end)
            && !$period->is_closed;
    }    

}