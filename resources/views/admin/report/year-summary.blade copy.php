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
                {{-- Card for  the Report Table --}}
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <!-- Previous month -->
                            <a href="{{ route('admin.reports.yearly-summary', ['year' => $summary['prevYear'] ]) }}" id="btn-prev-year" class="btn btn-sm btn-light" aria-label="前月">
                                <i class="fas fa-chevron-left"></i>
                            </a>                              
                            <!-- Month label -->
                            <div>
                                <h5 class="mb-0" id="h-month-label">{{ $summary['year'] }}年</h5>
                            </div>
                            <!-- Next month -->
                            <a href="{{ route('admin.reports.yearly-summary', ['year' => $summary['nextYear']]) }}" id="btn-next-year" class="btn btn-sm btn-light"aria-label="翌月">
                                <i class="fas fa-chevron-right"></i>
                            </a>                               
                        </div>                    
                    </div>
                    <div class="card-body">
                        <table id="monthlySummaryTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 15%">月</th>
                                    <th style="width: 15%">~</th>
                                    <th style="width: 15%">繰越金額</th>
                                    <th class="text-right">総収入</th>
                                    <th class="text-right">総支出</th>
                                    <th class="text-right">純残高</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary['monthlySummary'] as $month)
                                <tr>
                                    <td>{{ $month['month'] }}</td>
                                    <td>{{ $month['range'] }}</td>

                                    <td class="text-right">
                                        {{ number_format($month['totalIncome']) }} 円
                                    </td>
                                    
                                    <td class="text-right">
                                        {{ number_format($month['totalIncome']) }} 円
                                    </td>
                                    
                                    <td class="text-right">
                                        {{ number_format($month['totalExpense']) }} 円
                                    </td>
                                    
                                    {{-- Net Balance (Color changes based on positive/negative) --}}
                                    <td class="text-right @if($month['balance'] > 0) text-success @elseif($month['balance'] < 0) text-danger @else text-secondary @endif">
                                        {{ number_format($month['balance']) }} 円
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