<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AccountPeriod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\VirtualViewService;
use Illuminate\Support\Facades\Auth;
use App\Services\AccountPeriodService;
use Illuminate\Validation\ValidationException;

class AccountPeriodController extends Controller
{

    public function __construct(AccountPeriodService $service){
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * 
    */
    public function index(Request $request)
    {
        /**
         *  Config & inputs
        */
        $accountStartedYear  = config('constants.account_started_year');

        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        /**
         * Normalize inputs
        */
        if($month < 1 || $month > 12){
            $month = now()->month;
        }

        if($year < $accountStartedYear){
            $year = $accountStartedYear;
        }

        /**
         * Select lists
        */
        $years  = range($accountStartedYear, now()->year);
        $months = range(1, 12);


        /**
         * Period meta
        */
        $accountPeriodMeta  = VirtualViewService::getAccountPeriodByYearMonth($year, $month);

        /**
         * Account period (DB)
        */
        $accountPeriod = AccountPeriod::where('period_year', $year)
                            ->where('period_month', $month)
                            ->first();

        /**
         * Transactions (with eager loading)
        */
        $transactions = Transaction::with('transaction_type')
                            ->whereBetween('date',[
                                $accountPeriodMeta['periodStart'], 
                                $accountPeriodMeta['periodEnd']
                            ])
                            ->get();

        /**
         * Balances & status
        */
        $openingBalance = $accountPeriod ? $accountPeriod->opening_balance : 0;
        $statusClosed  = $accountPeriod ? $accountPeriod->is_closed : false; 
        
        /**
         * Totals
        */
        //$totalIncome = $transactions->where('transaction_type.name', '収入')->sum('amount');
        //$totalExpense = $transactions->where('transaction_type.name', '支出')->sum('amount'); 

        $totalIncome = $transactions
            ->filter(function ($t){ return $t->transaction_type->name === '収入';})
            ->sum('amount');

        $totalExpense = $transactions
            ->filter(function ($t){ return $t->transaction_type->name === '支出'; })
            ->sum('amount');
        
        //return view('admin.account_periods.index', compact('years', 'months', 'openingBalance', 'totalIncome', 'totalExpense', 'status_closed', 'period', 'accountPeriod'));
        /**
         * View 
        */
        return view('admin.account_periods.index', [
            'years'            => $years,
            'months'           => $months,
            'openingBalance'   => $openingBalance,
            'totalIncome'      => $totalIncome,
            'totalExpense'     => $totalExpense,
            'statusClosed'    => $statusClosed,
            'accountPeriodMeta'=> $accountPeriodMeta,
            'accountPeriod'    => $accountPeriod,
            'year'             => $year,
            'month'            => $month,
        ]);

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
     * @param  \App\Models\AccountPeriod  $accountPeriod
     * 
     */
    public function show(AccountPeriod $accountPeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountPeriod  $accountPeriod
     * 
     */
    public function edit(AccountPeriod $accountPeriod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountPeriod  $accountPeriod
     * 
     */
    public function update(Request $request, AccountPeriod $accountPeriod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountPeriod  $accountPeriod
     * 
     */
    public function destroy(AccountPeriod $accountPeriod)
    {
        //
    }

    /**
     * Closing the Account Period
     * @param Request $request
     *
     */
    public function close(Request $request){

        /**
         * Request validation
         */
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12'
        ]);

        $year = (int) $request->year;
        $month = (int) $request->month;
        $userId = auth()->id();

        
        /**
         * Resolve accounting period boundaries (cutoff-aware)
         */
        $closingPeriodMeta = VirtualViewService::getAccountPeriodByYearMonth($year, $month);

        /**
         * Validation: previous periods must be closed
         */
        $hasUnclosedPreviousPeriods = AccountPeriod::where('is_closed', false)
                                        ->where('end_date', '<', $closingPeriodMeta['periodStart'])
                                        ->exists();
        
        if($hasUnclosedPreviousPeriods){
            throw ValidationException::withMessages([
                //'period' => 'Previous account periods must be closed before closing this period.',
                'period' => '未締めの過去の会計期間があります。先に締め処理を行ってください',
            ]);
        }

        /**
        * Validation: transactions exist before first period
        */

        $hasPreviousTransactions = Transaction::where('date', '<', $closingPeriodMeta['periodStart'])
                                    ->exists();

        $hasAnyPreviousPeriod = AccountPeriod::where('end_date', '<', $closingPeriodMeta['periodStart'])
                                    ->exists();

        if ($hasPreviousTransactions && !$hasAnyPreviousPeriod) {
            throw ValidationException::withMessages([
                //'period' => 'Transactions exist in previous periods that have not been closed.',
                'period' => '未締めの過去の会計期間に取引データが存在します。',
            ]);
        }

        /**
         * Prevent future period closing
         */
        if($closingPeriodMeta['periodEnd']->gt(now()->endOfDay()) ){
            throw ValidationException::withMessages([
                //'period' => 'Future periods cannot be closed.',
                'period' => '将来の会計期間は締めることができません。',
            ]);
        }

        /**
         * Close period (atomic, concurrency-safe)
         */
        DB::transaction(function () use (
            $year, 
            $month, 
            $closingPeriodMeta
        ){

            /**
             * Lock & get current period (or create for first-time case)
             */                
            $closingAccountPeriod = AccountPeriod::where('period_year',$year)
                                ->where('period_month', $month)
                                ->lockForUpdate()
                                ->first();
            
            if(!$closingAccountPeriod){
                $closingAccountPeriod = AccountPeriod::create(
                    [
                        'user_id' => Auth::user()->id,
                        'period_year'  => $year,
                        'period_month' => $month,
                        'period_label'    => $closingPeriodMeta['month_label'],
                        'start_date'      => $closingPeriodMeta['periodStart'],
                        'end_date'        => $closingPeriodMeta['periodEnd'],
                        'opening_balance' => 0,
                        'closing_balance' => 0,
                        'is_closed'       => false,
                    ]
                );
            }

            if($closingAccountPeriod->is_closed){
                throw ValidationException::withMessages([
                    //'period' => 'This account period is already closed.',
                    'period' => 'この会計期間はすでに締め処理が完了しています。',
                ]);
            }

            
            /**
             * Calculate totals (DB-based, performant)
             */
            $transactions = Transaction::with('transaction_type')->whereBetween(
                'date',
                [$closingPeriodMeta['periodStart'], $closingPeriodMeta['periodEnd']]
            )->get();          
            $totalIncome = $transactions->filter(function($t){
                return $t->transaction_type->name === '収入';
            })->sum('amount');
            $totalExpense = $transactions->filter(function($t){
                return $t->transaction_type->name === '支出';
            })->sum('amount');


            /**
             * Close account period
             */
            $closingBalance = $closingAccountPeriod->opening_balance + $totalIncome - $totalExpense;

            $closingAccountPeriod->update([
                'closing_balance' => $closingBalance,
                'is_closed'       => true,
            ]);


            /**
            *  Create next account period (idempotent)
            */
            $nextBaseDate = \Carbon\Carbon::createFromDate($year, $month, 1)->addMonth();
            $nextPeriodMeta = VirtualViewService::getAccountPeriodByYearMonth($nextBaseDate->year, $nextBaseDate->month);
            
            AccountPeriod::firstOrCreate(
                [
                    'period_year'  => $nextPeriodMeta['year'],
                    'period_month' => $nextPeriodMeta['month'], 
                ],
                [
                    'user_id' => Auth::user()->id,
                    'period_label'    => $nextPeriodMeta['month_label'],
                    'start_date'      => $nextPeriodMeta['periodStart'],
                    'end_date'        => $nextPeriodMeta['periodEnd'],
                    'opening_balance' => $closingBalance,
                    'closing_balance' => 0,
                    'is_closed'       => false,
                ]
            );


            /**
             * (Optional but recommended) Lock transactions
             */
            //Transaction::whereBetween(
            //    'date',
            //    [$period['periodStart'], $period['periodEnd']]
            //)->update(['is_locked' => true]);            
            
        });

    return redirect()
        ->route('admin.account-periods.index', compact('year', 'month'))
        //->with('success', 'Account period closed successfully.');
        ->with('success', '会計期間の締め処理が正常に完了しました。');        
    }
}
