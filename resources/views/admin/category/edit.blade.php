@extends('layouts.admin')

@section('title', '勘定科目 編集')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>取引カテゴリー 編集</h1>
            </div>
        </div>
    </div></section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6"> 
                {{-- 編集を示す Warning カラーのカードを使用 --}}
                <div class="card card-warning"> 
                    <div class="card-header">
                        <h3 class="card-title">{{ $category->name }} の更新</h3>
                    </div>
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="card-body">
                            {{-- 取引区分 (Transaction Type) --}}
                            <div class="form-group">
                                <label for="transaction_type_id">取引タイプ:</label>
                                <select name="transaction_type_id" id="transaction_type_id" required
                                        class="form-control @error('transaction_type_id') is-invalid @enderror">
                                    <option value="">タイプを選択</option>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type->id }}" 
                                                {{ old('transaction_type_id', $category->transaction_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('transaction_type_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Name (名前) --}}
                            <div class="form-group">
                                <label for="name">名前:</label>
                                <input type="text" name="name" id="name" required
                                        class="form-control @error('name') is-invalid @enderror" 
                                        value="{{ old('name', $category->name) }}" 
                                        placeholder="例: 食費, 給与">
                                        
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> キャンセル
                            </a>
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