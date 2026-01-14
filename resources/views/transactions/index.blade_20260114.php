@extends('layouts.admin') {{-- Ensure you extend your AdminLTE layout --}}

@section('title', '取引')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>取引</h1>
            </div>
            <div class="col-sm-6 text-right">
                {{-- Add Transaction Button --}}
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> 取引を追加
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card card-outline card-info collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">絞り込みフィルター</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('transactions.index') }}">
                            <div class="row">
                                {{-- Filter: Type (Col-md-3) --}}
                                <div class="col-md-3 form-group">
                                    <label for="type">取引タイプ</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">すべて選択</option>
                                        @foreach($transactionTypes as $type)
                                            <option value="{{ $type->name }}" {{ request('type') == $type->name ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- Filter: Category (Col-md-3) --}}
                                <div class="col-md-3 form-group">
                                    <label for="category_id">取引カテゴリー</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">すべて選択</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ $category->transaction_type->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- Filter: Start Date (Col-md-2) --}}
                                <div class="col-md-2 form-group">
                                    <label for="start_date">開始日</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                                           class="form-control">
                                </div>
                                
                                {{-- Filter: End Date (Col-md-2) --}}
                                <div class="col-md-2 form-group">
                                    <label for="end_date">終了日</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                                           class="form-control">
                                </div>
                                
                                {{-- Buttons (Col-md-2) --}}
                                <div class="col-md-2 d-flex align-items-end form-group">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-filter"></i> 絞り込み
                                    </button>
                                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                        解除
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div>
                                <h3 class="card-title text-uppercase text-muted">
                                    <i class="fas fa-exchange-alt mr-1"></i> 取引 (Transactions)
                                </h3>
                                <br>
                                <small class="text-muted">
                                    {{ $dateFrom ?? 'Start' }} 〜 {{ $dateTo ?? 'End' }}
                                </small>
                            </div>

                            <div class="d-flex text-center mt-3 mt-md-0">
                                <div class="px-3 border-right">
                                    <div class="text-xs text-uppercase text-muted">収入 (Income)</div>
                                    <span class="text-success font-weight-bold">¥{{ number_format($totals->total_income ?? 0) }}</span>
                                </div>
                                <div class="px-3 border-right">
                                    <div class="text-xs text-uppercase text-muted">支出 (Expense)</div>
                                    <span class="text-danger font-weight-bold">¥{{ number_format($totals->total_expense ?? 0) }}</span>
                                </div>
                                <div class="px-3">
                                    <div class="text-xs text-uppercase text-muted">収支 (Net Amount)</div>
                                    <span class="text-primary font-weight-bold">
                                        ¥{{ number_format(($totals->total_income ?? 0) - ($totals->total_expense ?? 0)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>                   
                    <div class="card-body p-2">
                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">日付</th>
                                            <th style="width: 10%" >カ</th>
                                            <th style="width: 10%" >タ</th>
                                            <th >金額</th>
                                            <th style="width:15%">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td><span class="badge"> {{ $transaction->date->format('Y/m/d') }}</span>
                                                        
                                            </td>
                                            <td > <span class="badge bg-dark">{{ $transaction->category->name }}</span> </td>
                                            <td >
                                                {{-- Bootstrap Badge for Transaction Type --}}
                                                <span class="badge {{ $transaction->transaction_type->name === '収入' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $transaction->transaction_type->name }}
                                                </span>
                                            </td>                                            
                                            <td class="font-weight-bold">
                                                ¥{{ number_format($transaction->amount) }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-default open-action-modal" data-toggle="modal" data-t_id="{{ $transaction->id }}" data-t_date="{{ $transaction->date->format('Y/m/d') }}" data-t_category="{{ $transaction->category->name }}" data-t_amount="{{ number_format($transaction->amount)}}" data-target="#modal-table-action">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card-body">
                                <div class="text-center py-4">
                                    <p class="text-muted text-lg">取引が見つかりません。</p>
                                    <a href="{{ route('transactions.create') }}" class="btn btn-success mt-3">
                                        <i class="fas fa-plus"></i> 最初の取引を作成
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if($transactions->count() > 0)
                    <div class="card-footer clearfix">
                        {{-- Laravel Pagination with Bootstrap Styling --}}
                        {{ $transactions->links() }}
                    </div>
                    @endif
                </div>
                </div>
        </div>
        </div>
        <div class="modal fade" id="modal-table-action">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="modal-title">Actions</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-table-action-body">
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <!--button type="button" class="btn btn-primary">Save changes</button-->
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
        <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- comment -->
        
        
    
</section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('click', function(e){
            const btn = e.target.closest('.open-action-modal');
            if(!btn) return;

            const id = btn.dataset.t_id;
            const title = `取引: ${btn.dataset.t_date} ${btn.dataset.t_category} ¥${btn.dataset.t_amount}`;

            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-table-action-body').innerHTML = 
            `
            <a href="transactions/${id}" class="btn btn-info btn-sm" title="表示"><i class="fas fa-eye"></i> 表示</a>
            <a href="transactions/${id}/edit" class="btn btn-warning btn-sm" title="編集"><i class="fas fa-edit"></i> 編集</a>
            <form action="transactions/${id}" method="POST" class="d-inline" onsubmit="return confirm('削除してもよろしいですか？')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" title="削除">
                    <i class="fas fa-trash"></i> 削除
                </button>
            </form>            
            `;

            //new bootstrap.Modal('#modal-table-action').show();

        });
    </script>
@endsection