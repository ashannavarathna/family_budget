@extends('layouts.app')

@section('title', '家計簿へようこそ')

{{-- 画面全体にフィットし、中央揃えを可能にするbody_classを適用 --}}
@section('body_class', 'hold-transition')

@section('content')
{{-- 以下の<div>が、画面いっぱいに広がり、内部のコンテンツを垂直・水平方向に中央揃えします --}}
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-8 col-lg-6"> {{-- 中央揃えを適用するため、コンテナを直接削除し、col-md-8を親に昇格 --}}
        <div class="card card-primary card-outline w-100">
            <div class="card-header text-center">
                <h1 class="h1"><b>家計簿</b> 1.0</h1>
            </div>
            <div class="card-body text-center">
                <h2 class="h3 mb-4 text-gray-800">
                    家計簿へようこそ
                </h2>
                <p class="lead text-gray-600 mb-5">
                    家計を管理し、収入と支出を追跡し、家計目標を達成しましょう。
                </p>

                <div class="row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        {{-- ログインボタン --}}
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-sign-in-alt mr-2"></i> ログイン
                        </a>
                    </div>
                    <div class="col-sm-6">
                        {{-- 登録ボタン --}}
                        <a href="{{ route('register') }}" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-user-plus mr-2"></i> 登録
                        </a>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection