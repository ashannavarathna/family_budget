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
                    <div class="row">
                        @foreach($summary['monthlySummary'] as $month)
                            <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                                <div class="card shadow-sm p-2 h-100 border-bottom border-2">
                                    <div class="card-body p-2">

                                        <!-- Top Line: Month / Carry Over -->
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge badge-dark badge-xs">{{ $month['month'] }}月</span>
                                            <span class="badge badge-info ">繰越: ¥{{ number_format($month['carryOver'] ?? 0) }}</span>
                                        </div>

                                        <!-- Bottom Section: Date Range (left) + stacked numbers (right) -->
                                        <div class="d-flex justify-content-between align-items-start">
                                            <!-- Left: Date Range -->
                                            <span class="badge badge-secondary badge-xs">{{ $month['range'] }}</span>

                                            <!-- Right: stacked numbers -->
                                            <div class="d-flex flex-column align-items-end" style="gap: 0.15rem;">
                                                <span class="badge badge-success ">収入: ¥{{ number_format($month['totalIncome']) }}</span>
                                                <span class="badge badge-danger ">支出: ¥{{ number_format($month['totalExpense']) }}</span>
                                                <span class="badge {{ $month['balance'] >= 0 ? 'badge-success' : 'badge-danger' }} ">
                                                    残高: ¥{{ number_format($month['balance']) }}
                                                </span>
                                            </div>
                                        </div>

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