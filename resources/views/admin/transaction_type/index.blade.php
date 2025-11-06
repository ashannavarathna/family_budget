@extends('layouts.admin') {{-- NOTE: AdminLTEの基本レイアウトを継承します --}}

@section('title', '取引区分管理')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>取引タイプ</h1>
            </div>
            <div class="col-sm-6 text-right">
                {{-- 新規作成ボタンを右に配置 (AdminLTEの標準的な青いボタン) --}}
                <a href="{{ route('admin.transaction_types.create') }}" class="btn btn-primary float-sm-right" title="新規作成">
                    <i class="fas fa-plus"></i> 新規作成
                </a>
            </div>
        </div>
    </div></section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- 取引区分リストの表示にCardを使用 --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">取引タイプリスト</h3>
                    </div>
                    <div class="card-body p-0">
                        {{-- Bootstrapのテーブルクラスを適用 --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        {{-- ヘッダーセルにAdminLTEのクラスを適用 --}}
                                        <th style="width: 50px">Id</th>
                                        <th>名前</th>
                                        <th style="width: 150px" class="text-center">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactionTypes as $transactionType)
                                    <tr>
                                        <td>{{ $transactionType->id }}</td>
                                        <td>
                                            {{-- 取引区分によってバッジの色を変更 (収入/支出などで色を使い分けたい場合はここにロジックを追加) --}}
                                            <span class="badge {{ $transactionType->name === '収入' ? 'bg-success' : ($transactionType->name === '支出' ? 'bg-danger' : 'bg-secondary') }}">
                                                {{ $transactionType->name}}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                {{-- 編集ボタン (黄色/Warning) --}}
                                                <a href="{{ route('admin.transaction_types.edit', $transactionType) }}" class="btn btn-warning" title="編集">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                {{-- 削除フォーム (赤/Danger) --}}
                                                <form action="{{ route('admin.transaction_types.destroy', $transactionType) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除してもよろしいですか？')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="削除">
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
                    </div>
                    {{-- NOTE: もしページネーション ($transactionTypes->links())を使用する場合は、card-footerに追加します。 --}}
                </div>
                </div>
        </div>
    </div></section>

{{-- NOTE: 元のファイルのモバイルメニューJavaScriptは、AdminLTEが独自のレスポンシブナビゲーションを提供するため削除しました。 --}}
@endsection