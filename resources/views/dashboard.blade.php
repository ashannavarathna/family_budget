@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                {{-- Breadcrumbs or other header elements go here if needed --}}
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">月次概要 ({{ date('Y') }})</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">月</th>
                                        <th style="width: 30%">収入</th>
                                        <th style="width: 30%">支出</th>
                                        <th style="width: 30%">残高</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlySummary as $summary)
                                    <tr>
                                        <td>{{ DateTime::createFromFormat('!m', $summary->month)->format('F (m)') }}</td>
                                        <td class="text-success">¥{{ number_format($summary->total_income) }}</td>
                                        <td class="text-danger">¥{{ number_format($summary->total_expense) }}</td>
                                        <td class="font-weight-bold {{ $summary->net_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                            ¥{{ number_format($summary->net_balance) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">直近の取引</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>日付</th>
                                        <th>カテゴリー</th>
                                        <th>備考</th>
                                        <th>金額</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->date }}</td>
                                        <td>{{ $transaction->category_name }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td class="{{ $transaction->type_name === '収入' ? 'text-success' : 'text-danger' }}">
                                            ¥{{ number_format($transaction->amount) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline"> {{-- Using AdminLTE "card-outline" for a clean look --}}
                    <div class="card-header">
                        <h3 class="card-title">取引タイプ別の概要</h3>
                    </div>
                    <div class="card-body">
                        <div class="row"> {{-- Inner Bootstrap row for the grid --}}
                            @foreach($summaryByType as $summary)
                            <div class="col-md-6 mb-3">
                                <div class="info-box {{ $summary->type === '収入' ? 'bg-success' : 'bg-danger' }}">
                                    <span class="info-box-icon"><i class="fas {{ $summary->type === '収入' ? 'fa-plus' : 'fa-minus' }}"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $summary->type }}</span>
                                        <span class="info-box-number">¥{{ number_format($summary->total_amount) }}</span>
                                        <span class="progress-description">
                                            {{ $summary->transaction_count }} 取引
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">年度比較</h3>
                    </div>
                    <div class="card-body">
                        <div class="row"> {{-- Inner Bootstrap row for the grid --}}
                            @foreach($typeStatistics as $stat)
                            <div class="col-md-6 mb-3">
                                <div class="info-box {{ $stat['type'] === '収入' ? 'bg-white border border-success' : 'bg-white border border-danger' }}">
                                    <span class="info-box-icon {{ $stat['type'] === '収入' ? 'bg-success' : 'bg-danger' }}"><i class="fas fa-chart-line"></i></span>
                                    <div class="info-box-content text-dark">
                                        <span class="info-box-text">{{ $stat['type'] }}</span>
                                        <span class="info-box-number">¥{{ number_format($stat['current_year_total']) }}</span>
                                        <span class="progress-description {{ $stat['growth_percentage'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            <i class="fas {{ $stat['growth_percentage'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                            {{ $stat['growth_percentage'] >= 0 ? '+' : '' }}{{ $stat['growth_percentage'] }}%
                                            <small class="text-muted d-block">前年: ¥{{ number_format($stat['previous_year_total']) }}</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div></section>
@endsection