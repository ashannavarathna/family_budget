@extends('layouts.app')

@section('title', '取引')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">取引</h1>
            <a href="{{ route('transactions.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                取引を追加
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">種別</label>
                    <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">すべて選択</option>
                        @foreach($transactionTypes as $type)
                            <option value="{{ $type->name }}" {{ request('type') == $type->name ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">区分</label>
                    <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">すべて選択</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->transaction_type->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">開始日</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">終了日</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-4 flex space-x-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        絞り込み
                    </button>
                    <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        解除
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($transactions->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">日付</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6 hidden sm:table-cell">区分</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">金額</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6 hidden md:table-cell">種別</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6">操作</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 sm:px-6">
                                            {{ $transaction->date->format('m/d/Y') }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 sm:px-6 hidden sm:table-cell">
                                            {{ $transaction->category->name }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-semibold {{ $transaction->transaction_type->name === '収入' ? 'text-green-600' : 'text-red-600' }} sm:px-6">
                                            ¥{{ number_format($transaction->amount) }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap hidden md:table-cell sm:px-6">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->transaction_type->name === '収入' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($transaction->transaction_type->name) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium sm:px-6">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-900">表示</a>
                                                <a href="{{ route('transactions.edit', $transaction) }}" class="text-green-600 hover:text-green-900">編集</a>
                                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">削除</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg">取引が見つかりません。</p>
                        <a href="{{ route('transactions.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            最初の取引を作成
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
/**
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category_id');

    // store all category data first
    const allCategories = Array.from(categorySelect.querySelectorAll('option'))
        .map(option =>({
            id: option.value,
            name: option.textContent,
            typeId: option.dataset.type
        }))
        .filter(opt => opt.id !== ''); 

    function filterCategories() {
        const selectedType = typeSelect.value;
        const oldValue = categorySelect.value;

        // Clear current options
        categorySelect.innerHTML = '<option value="">区分選択</option>';

        // Add only matching allCategories
        const filtered = allCategories.filter(cat => selectedType === '' || cat.typeId === selectedType);
        
        console.log(filtered);

        filtered.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            categorySelect.appendChild(option);
        });
        // Restore old value if still valid
        if(filtered.some(cat => cat.id === oldValue)){
            categorySelect.value = oldValue;
        }

    }

    typeSelect.addEventListener('change', filterCategories);
    
    // Initial filter on page load
    filterCategories();
});
*/
</script>
@endsection