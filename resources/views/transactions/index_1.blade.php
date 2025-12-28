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
                    <div class="card-body p-0">
                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">日付</th>
                                            <th style="width: 20%;" class="d-none d-sm-table-cell">カテゴリー</th>
                                            <th >金額</th>
                                            <th style="width: 10%;" class="d-none d-md-table-cell">タイプ</th>
                                            <th style="width: 15%;">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td><span class="badge"> {{ $transaction->date->format('Y/m/d') }}</span>
                                                        
                                            </td>
                                            <td class="d-none d-sm-table-cell"> <span class="badge">{{ $transaction->category->name }}</span> </td>
                                            <td class="font-weight-bold {{ $transaction->transaction_type->name === '収入' ? 'text-success' : 'text-danger' }}">
                                                ¥{{ number_format($transaction->amount) }}
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                {{-- Bootstrap Badge for Transaction Type --}}
                                                <span class="badge {{ $transaction->transaction_type->name === '収入' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $transaction->transaction_type->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group">
                                                    <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-id="{{ $transaction->transaction_id }}" data-target="#modal-table-action">
                                                        actions
                                                    </button>
                                                    <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-info btn-sm" title="表示">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-warning btn-sm" title="編集">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('削除してもよろしいですか？')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="削除">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
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
                  <h4 class="modal-title">Actions</h4>
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
    
</section>
@endsection