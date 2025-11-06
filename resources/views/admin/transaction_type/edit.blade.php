@extends('layouts.admin')

@section('title', '取引区分 編集')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>取引タイプ 編集</h1>
            </div>
        </div>
    </div></section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            {{-- フォームを中央に寄せるために幅を制限 --}}
            <div class="col-md-6"> 
                {{-- 編集を示す Warning カラーのカードを使用 --}}
                <div class="card card-warning"> 
                    <div class="card-header">
                        <h3 class="card-title">{{ $transactionType->name }} の更新</h3>
                    </div>
                    <form action="{{ route('admin.transaction_types.update', $transactionType) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- 更新にはPUTメソッドが必要 --}}
                        
                        <div class="card-body">
                            {{-- Name (名前) --}}
                            <div class="form-group">
                                <label for="name">名前:</label>
                                <input type="text" name="name" id="name" required
                                        class="form-control @error('name') is-invalid @enderror" 
                                        value="{{ old('name', $transactionType->name) }}" 
                                        placeholder="例: 収入, 支出">
                                        
                                @error('name')
                                    {{-- エラーメッセージはBootstrapのクラスで表示 --}}
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            {{-- キャンセルボタン (デフォルト/灰色) --}}
                            <a href="{{ route('admin.transaction_types.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> キャンセル
                            </a>
                            {{-- 更新ボタン (警告/黄色) --}}
                            <button type="submit" class="btn btn-warning float-right">
                                <i class="fas fa-edit"></i> 更新
                            </button>
                        </div>
                        </form>
                </div>
                </div>
        </div>
    </div></section>
@endsection