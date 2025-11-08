@extends('layouts.admin')

@section('title', '月間財務サマリー')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>月間財務サマリー</h1>
            </div>
        </div>
    </div></section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- Card for the Report Table --}}
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">月ごとの収支結果</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="monthlySummaryTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 15%">年</th>
                                    <th style="width: 15%">月</th>
                                    <th class="text-right">総収入</th>
                                    <th class="text-right">総支出</th>
                                    <th class="text-right">純残高</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlySummary as $summary)
                                <tr>
                                    <td>{{ $summary->year }}年</td>
                                    <td>{{ $summary->month }}月</td>
                                    
                                    {{-- Total Income (Green for positive value) --}}
                                    <td class="text-right text-success font-weight-bold">
                                        {{ number_format($summary->total_income) }} 円
                                    </td>
                                    
                                    {{-- Total Expense (Red for negative implication) --}}
                                    <td class="text-right text-danger">
                                        {{ number_format($summary->total_expense) }} 円
                                    </td>
                                    
                                    {{-- Net Balance (Color changes based on positive/negative) --}}
                                    <td class="text-right font-weight-bold @if($summary->net_balance > 0) text-success @elseif($summary->net_balance < 0) text-danger @else text-secondary @endif">
                                        {{ number_format($summary->net_balance) }} 円
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">データがありません。取引を登録してください。</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
        </div>
    </div></section>
@endsection