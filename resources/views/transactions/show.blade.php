@extends('layouts.admin') {{-- Ensure this extends your AdminLTE layout --}}

@section('title', '取引詳細')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>取引詳細</h1>
            </div>
            <div class="col-sm-6">
                {{-- Action buttons aligned right --}}
                <div class="float-sm-right btn-group">
                    {{-- Edit Button (Green -> Warning/Yellow in AdminLTE) --}}
                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-warning" title="編集">
                        <i class="fas fa-edit"></i> 編集
                    </a>
                    
                    {{-- Delete Button (Red) --}}
                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" 
                          onsubmit="return confirm('削除してもよろしいですか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="削除">
                            <i class="fas fa-trash"></i> 削除
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            {{-- Using col-md-8 to limit width, similar to max-w-3xl --}}
            <div class="col-md-8"> 
                <div class="card card-info card-outline"> {{-- Using a clean card style --}}
                    <div class="card-header">
                        <h3 class="card-title">取引情報</h3>
                    </div>
                    <div class="card-body">
                        {{-- Detail list using Bootstrap's grid system for definition list style --}}
                        
                        <dl class="row mb-0">
                            {{-- Date --}}
                            <dt class="col-sm-4 text-muted">日付</dt>
                            <dd class="col-sm-8">{{ $transaction->date->format('Y年n月j日') }}</dd>

                            {{-- Type --}}
                            <dt class="col-sm-4 text-muted">取引タイプ</dt>
                            <dd class="col-sm-8">
                                {{-- Bootstrap Badge for Transaction Type --}}
                                <span class="badge {{ $transaction->transaction_type->name === '収入' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($transaction->transaction_type->name) }}
                                </span>
                            </dd>

                            {{-- Category --}}
                            <dt class="col-sm-4 text-muted">取引カテゴリー</dt>
                            <dd class="col-sm-8">{{ $transaction->category->name }}</dd>

                            {{-- Account --}}
                            <dt class="col-sm-4 text-muted">収支担当者</dt>
                            <dd class="col-sm-8">{{ $transaction->account->name }}</dd>

                            {{-- Party --}}
                            <dt class="col-sm-4 text-muted">収支先</dt>
                            <dd class="col-sm-8">{{ $transaction->party }}</dd>

                            {{-- Receipt Number --}}
                            <dt class="col-sm-4 text-muted">領収書番号</dt>
                            <dd class="col-sm-8">{{ $transaction->receipt_number }}</dd>

                            {{-- Amount --}}
                            <dt class="col-sm-4 text-muted">金額</dt>
                            <dd class="col-sm-8 font-weight-bold {{ $transaction->transaction_type->name === '収入' ? 'text-success' : 'text-danger' }}">
                                ¥{{ number_format($transaction->amount) }}
                            </dd>

                            {{-- Description --}}
                            <dt class="col-sm-4 text-muted">備考</dt>
                            <dd class="col-sm-8 text-break">{{ $transaction->description ?? 'N/A' }}</dd>
                            
                            {{-- Separator for audit fields --}}
                            <div class="col-12"><hr class="my-3"></div>

                            {{-- Created By --}}
                            <dt class="col-sm-4 text-muted">作成者</dt>
                            <dd class="col-sm-8">{{ $transaction->user->name ?? 'N/A' }}</dd>

                            {{-- Created At --}}
                            <dt class="col-sm-4 text-muted">作成日時</dt>
                            <dd class="col-sm-8">{{ $transaction->created_at->format('Y/m/d H:i') }}</dd>

                            {{-- Updated At --}}
                            <dt class="col-sm-4 text-muted">更新日時</dt>
                            <dd class="col-sm-8">{{ $transaction->updated_at->format('Y/m/d H:i') }}</dd>
                        </dl>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('transactions.index') }}" class="btn btn-default">
                            <i class="fas fa-chevron-left"></i> 取引一覧に戻る
                        </a>
                    </div>
                    </div>
                </div>
        </div>
    </div></section>
@endsection