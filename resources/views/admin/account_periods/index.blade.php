@extends('layouts.admin') {{-- NOTE: AdminLTEの基本レイアウトを継承します --}}

@section('title', '決算期')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>決算期</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card card-info">
                            <div class="card-header text-center">
                                <h4 class="card-title mb-0">
                                    Account Period Closing
                                </h4>
                            </div>

                            <div class="card-body text-center">
                                <form method="GET" action="{{ route('admin.account-periods.index') }}">
                                    <div class="row justify-content-center">
                                            <!-- Year -->
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <span class="text-muted">年 (Year)</span>
                                                    <select name="year" class="form-control" onchange="this.form.submit()">
                                                        @foreach($years as $year)
                                                            <option value="{{ $year }}"
                                                                {{ request('year', now()->year) == $year ? 'selected' : '' }}>
                                                                {{ $year }}年
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Month -->
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <span class="text-muted">月 (Month)</span>
                                                    <select name="month" class="form-control" onchange="this.form.submit()">
                                                        @for($m = 1; $m <= 12; $m++)
                                                            <option value="{{ $m }}"
                                                                {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                                                {{ $m }}月
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>                                    
                                    </div>
                                </form>
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <span class="badge bg-dark">Account Period:</span> 
                                        <span class="badge badge-secondary">{{ $accountPeriodMeta['periodStart']->toDateString() ?? 'Start' }}</span> 〜 <span class="badge badge-secondary">{{ $accountPeriodMeta['periodEnd']->toDateString() ?? 'End' }}</span>
                                    </div>
                                </div>


                                <hr>

                                <div class="row mb-3">
                                    <div class="col-4">
                                        <div class="text-xs text-uppercase text-muted">期首残高 <span class="d-block">(Opening)</span></div>
                                        <strong class="d-block">
                                            ¥{{ number_format($openingBalance) }}
                                        </strong>
                                    </div>

                                    <div class="col-4">
                                        <div class="text-xs text-uppercase text-muted">収入 <span class="d-block">(Income)</span></div>
                                        <strong class="text-success d-block">
                                            ¥{{ number_format($totalIncome) }}
                                        </strong>
                                    </div>

                                    <div class="col-4">
                                        <div class="text-xs text-uppercase text-muted">支出 <span class="d-block">(Expense)</span></div>
                                        <strong class="text-danger d-block">
                                            ¥{{ number_format($totalExpense) }}
                                        </strong>
                                    </div>
                                </div>

                                <hr>

                                <h3 class="">
                                    <span class="text-uppercase text-muted" style="font-size: 1.15rem;">期末残高 <span class="d-block">(Closing)</span></span>
                                    <strong class="text-danger d-block">
                                    ¥{{ number_format(($openingBalance + $totalIncome) - $totalExpense)  }}
                                    </strong>
                                </h3>

                                <hr>
                                <form method="POST" action="{{ route('admin.account-periods.close') }}">
                                    @csrf
                                        <input type="hidden" name="year" value="{{ request('year', now()->year) }}">
                                        <input type="hidden" name="month" value="{{ request('month', now()->month) }}">
                                    <button
                                        type="submit"
                                        class="btn btn-success btn-lg mb-3" {{ $statusClosed ? 'disabled' : '' }}
                                        @unless($statusClosed)
                                            onclick="return confirm('会計期間を締めてもよろしいですか?')"
                                        @endunless
                                        >
                                        {{ $statusClosed ? '決算済み' : '決算処理' }}
                                    </button>
                                    
                                    @if(session('success'))
                                        <div class="callout callout-success">
                                            <h5 class="text-success"><i class="fas fa-check-circle"></i> 処理完了</h5>
                                            <p class="text-success">{{ session('success') }}</p>
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="callout callout-danger">
                                            <h5 class="text-danger"><i class="fas fa-exclamation-triangle"></i> エラー</h5>
                                            <p class="text-danger">{{ $errors->first() }}</p>
                                        </div>
                                    @endif

                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection