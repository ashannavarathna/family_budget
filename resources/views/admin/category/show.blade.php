@extends('layouts.admin')

@section('title', '取引カテゴリー 詳細')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>取引カテゴリー 詳細</h1>
            </div>
            <div class="col-sm-6">
                {{-- 操作ボタンを右に配置 --}}
                <div class="float-sm-right">
                    {{-- 編集ボタン (routeはadmin.category.edit) --}}
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> 編集
                    </a>
                    {{-- 一覧へ戻るボタン (routeはadmin.category.index) --}}
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> 一覧へ戻る
                    </a>
                </div>
            </div>
        </div>
    </div></section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8"> 
                {{-- 詳細表示に info カラーのカードを使用 --}}
                <div class="card card-info"> 
                    <div class="card-header">
                        <h3 class="card-title">{{ $category->name }} の詳細情報</h3>
                    </div>
                    <div class="card-body">
                        {{-- 情報をDescription List形式で表示 --}}
                        <dl class="row">
                            {{-- ID --}}
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8">{{ $category->id }}</dd>

                            {{-- 名前 --}}
                            <dt class="col-sm-4">名前:</dt>
                            <dd class="col-sm-8">{{ $category->name }}</dd>
                            
                            {{-- 取引区分 --}}
                            <dt class="col-sm-4">取引タイプ:</dt>
                            <dd class="col-sm-8">
                                {{-- $category->transaction_type は Category.php で定義されています --}}
                                <span class="badge {{ $category->transaction_type->name === '収入' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $category->transaction_type->name }}
                                </span>
                            </dd>

                            {{-- 作成日 --}}
                            <dt class="col-sm-4">作成日:</dt>
                            <dd class="col-sm-8">{{ $category->created_at ? $category->created_at->format('Y/m/d H:i') : '-' }}</dd>

                        </dl>
                        
                        {{-- 削除ボタンは詳細画面下部にも配置することが多い --}}
                        <hr>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('本当にこの取引カテゴリー「{{ $category->name }}」を削除してもよろしいですか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> 取引カテゴリーを削除
                            </button>
                        </form>
                    </div>
                    </div>
                </div>
        </div>
    </div></section>
@endsection