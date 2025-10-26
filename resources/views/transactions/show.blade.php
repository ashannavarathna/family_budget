@extends('layouts.app')

@section('title', '取引詳細')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">取引詳細</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('transactions.edit', $transaction) }}" 
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            編集
                        </a>
                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                削除
                            </button>
                        </form>
                    </div>
                </div>

                <div class="border-t border-gray-200">
                    <dl class="divide-y divide-gray-200">
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">日付</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->date->format('F j, Y') }}</dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">種別</dt>
                            <dd class="text-sm text-gray-900 col-span-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->transaction_type->name === '収入' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transaction->transaction_type->name) }}
                                </span>
                            </dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">区分</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->category->name }}</dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">収支担当者</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->account->name }}</dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">相手先</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->party }}</dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">領収書番号</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->receipt_number }}</dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">金額</dt>
                            <dd class="text-sm font-semibold {{ $transaction->transaction_type->name === 'income' ? 'text-green-600' : 'text-red-600' }} col-span-2">
                                ¥{{ number_format($transaction->amount, 2) }}
                            </dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">備考</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->description ?? 'N/A' }}</dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">作成者</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->user->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">作成日時</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->created_at->format('F j, Y g:i A') }}</dd>
                        </div>
                        <div class="py-4 grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">更新日時</dt>
                            <dd class="text-sm text-gray-900 col-span-2">{{ $transaction->updated_at->format('F j, Y g:i A') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-6">
                    <a href="{{ route('transactions.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        取引一覧に戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection