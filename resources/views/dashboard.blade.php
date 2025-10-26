@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">ダッシュボード</h1>
        
        <!-- Monthly Summary -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">月次概要 ({{ date('Y') }})</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">月</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">収入</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">支出</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">残高</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($monthlySummary as $summary)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ DateTime::createFromFormat('!m', $summary->month)->format('F (m)') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">¥{{ number_format($summary->total_income) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">¥{{ number_format($summary->total_expense) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $summary->net_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ¥{{ number_format($summary->net_balance) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">直近の取引</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日付</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">区分</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">備考</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">金額</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->category_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->type_name === '収入' ? 'text-green-600' : 'text-red-600' }}">
                                    ¥{{ number_format($transaction->amount) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary by Type -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">種別別の概要</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($summaryByType as $summary)
                <div class="border rounded-lg p-4 {{ $summary->type === '収入' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                    <h4 class="font-semibold capitalize {{ $summary->type === '収入' ? 'text-green-800' : 'text-red-800' }}">
                        {{ $summary->type }}
                    </h4>
                    <p class="text-2xl font-bold {{ $summary->type === '収入' ? 'text-green-600' : 'text-red-600' }}">
                        ¥{{ number_format($summary->total_amount) }}
                    </p>
                    <p class="text-sm text-gray-600">{{ $summary->transaction_count }} 取引</p>
                    <!--p class="text-sm text-gray-600">平均: ¥{{ number_format($summary->average_amount) }}</p-->
                </div>
                @endforeach
            </div>
        </div>

        <!-- Type Statistics with Growth -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">年度比較</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($typeStatistics as $stat)
                <div class="border rounded-lg p-4 {{ $stat['type'] === '収入' ? 'border-green-200' : 'border-red-200' }}">
                    <h4 class="font-semibold capitalize {{ $stat['type'] === '収入' ? 'text-green-800' : 'text-red-800' }}">
                        {{ $stat['type'] }}
                    </h4>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-lg font-bold {{ $stat['type'] === '収入' ? 'text-green-600' : 'text-red-600' }}">
                                ¥{{ number_format($stat['current_year_total']) }}
                            </p>
                            <p class="text-sm text-gray-600">前年: ¥{{ number_format($stat['previous_year_total']) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stat['growth_percentage'] >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $stat['growth_percentage'] >= 0 ? '+' : '' }}{{ $stat['growth_percentage'] }}%
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection