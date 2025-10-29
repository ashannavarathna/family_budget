@extends('layouts.app')

@section('title', 'Create Transaction')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">取引を追加</h1>

                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Transaction Type -->
                        <div>
                            <label for="transaction_type_id" class="block text-sm font-medium text-gray-700">取引種別 *</label>
                            <select name="transaction_type_id" id="transaction_type_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">種別選択</option>
                                @foreach($transactionTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('transaction_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('transaction_type_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">区分 *</label>
                            <select name="category_id" id="category_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">区分選択</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            data-type="{{ $category->transaction_type_id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Account -->
                        <div>
                            <label for="account_id" class="block text-sm font-medium text-gray-700">収支担当者 *</label>
                            <select name="account_id" id="account_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">収支担当者選択</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" 
                                            {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">日付 *</label>
                            <input type="date" name="date" id="date" required
                                   value="{{ old('date', date('Y-m-d')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 相手先 -->
                        <div>
                            <label for="party" class="block text-sm font-medium text-gray-700">相手先 *</label>
                            <input type="text" name="party" id="party" required
                                   value="{{ old('party') }}" placeholder="相手先"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('party')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 領収書番号 -->
                        <div>
                            <label for="receipt_number" class="block text-sm font-medium text-gray-700">領収書番号 *</label>
                            <input type="text" name="receipt_number" id="receipt_number" required 
                                   value="{{ old('receipt_number') }}" placeholder="領収書番号"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('receipt_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">金額 *</label>
                            <input type="number" name="amount" id="amount" required step="0.01" min="0.01"
                                   value="{{ old('amount') }}" placeholder="0.00"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">備考</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="任意の備考">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('transactions.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            取り消し
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            追加
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

/**
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('transaction_type_id');
    const categorySelect = document.getElementById('category_id');
    const categoryOptions = categorySelect.querySelectorAll('option');

    function filterCategories() {
        const selectedType = typeSelect.value;
        
        categoryOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            if (selectedType === '' || option.getAttribute('data-type') === selectedType) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                if (option.selected) {
                    option.selected = false;
                }
            }
        });
    }

    typeSelect.addEventListener('change', filterCategories);
    
    // Initial filter on page load
    filterCategories();
});

*/

/** 20251027 updated 
 *  reason : mobile browsers handle <option style="display:none"> 
    differently than desktop browsers.

 *  problem : Most mobile browsers (especially Safari, Chrome on Android, iOS WebView) 
    do not hide <option> elements with display:none — they still appear in the dropdown list
 *  solution : Instead of hiding <option> elements, 

    recreate the category list dynamically each time the user changes the transaction type.
*/


document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('transaction_type_id');
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

</script>
@endsection