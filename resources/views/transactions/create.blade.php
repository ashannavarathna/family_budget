@extends('layouts.admin') {{-- NOTE: Ensure this matches your AdminLTE layout file name --}}

@section('title', '取引を追加')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>取引を追加</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            {{-- Using col-md-8 to limit width, similar to max-w-3xl --}}
            <div class="col-md-8"> 
                <div class="card card-primary"> {{-- Using the card-primary color scheme --}}
                    <div class="card-header">
                        <h3 class="card-title">新しい取引の登録</h3>
                    </div>
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            {{-- Bootstrap row and form-group replace the Tailwind grid and spacing --}}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="transaction_type_id">取引タイプ *</label>
                                    <select name="transaction_type_id" id="transaction_type_id" required
                                            class="form-control @error('transaction_type_id') is-invalid @enderror">
                                        <option value="">タイプ選択</option>
                                        @foreach($transactionTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('transaction_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('transaction_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="category_id">取引カテゴリー *</label>
                                    <select name="category_id" id="category_id" required
                                            class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">カテゴリー選択</option>
                                        @foreach($categories as $category)
                                            {{-- Data attribute is crucial for JavaScript filtering --}}
                                            <option value="{{ $category->id }}" 
                                                    data-type="{{ $category->transaction_type_id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="account_id">収支担当者 *</label>
                                    <select name="account_id" id="account_id" required
                                            class="form-control @error('account_id') is-invalid @enderror">
                                        <option value="">収支担当者選択</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" 
                                                    {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="date">日付 *</label>
                                    <input type="date" name="date" id="date" required
                                            value="{{ old('date', date('Y-m-d')) }}"
                                            class="form-control @error('date') is-invalid @enderror">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="party">収支先 *</label>
                                    <input type="text" name="party" id="party" required
                                            value="{{ old('party') }}" placeholder="収支先"
                                            class="form-control @error('party') is-invalid @enderror">
                                    @error('party')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="receipt_number">領収書番号 *</label>
                                    <input type="text" name="receipt_number" id="receipt_number" required 
                                            value="{{ old('receipt_number') }}" placeholder="領収書番号"
                                            class="form-control @error('receipt_number') is-invalid @enderror">
                                    @error('receipt_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="amount">金額 *</label>
                                    <input type="number" name="amount" id="amount" required step="0.01" min="0.01"
                                            value="{{ old('amount') }}" placeholder="0.00"
                                            class="form-control @error('amount') is-invalid @enderror">
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="description">備考</label>
                                    <textarea name="description" id="description" rows="3"
                                                class="form-control @error('description') is-invalid @enderror"
                                                placeholder="任意の備考">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <a href="{{ route('transactions.index') }}" class="btn btn-default">
                                <i class="fas fa-times"></i> 取り消し
                            </a>
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fas fa-save"></i> 追加
                            </button>
                        </div>
                    </form>
                </div>
                </div>
        </div>
    </div></section>

{{-- JavaScript should be placed here, or ideally pushed to a script stack in your layout --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('transaction_type_id');
    const categorySelect = document.getElementById('category_id');

    // Store all category data first
    const allCategories = Array.from(categorySelect.querySelectorAll('option'))
        .map(option =>({
            id: option.value,
            name: option.textContent.trim(),
            typeId: option.dataset.type
        }))
        .filter(opt => opt.id !== ''); 

    function filterCategories() {
        const selectedType = typeSelect.value;
        const oldValue = categorySelect.value;

        // Clear current options
        categorySelect.innerHTML = '<option value="">カテゴリー選択</option>';

        // Add only matching allCategories
        const filtered = allCategories.filter(cat => selectedType === '' || cat.typeId === selectedType);
        
        filtered.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            // Re-add the data-type attribute for consistency/future use
            option.setAttribute('data-type', cat.typeId); 
            categorySelect.appendChild(option);
        });

        // Restore old value if still valid
        if(filtered.some(cat => cat.id === oldValue)){
            categorySelect.value = oldValue;
        } else {
            // If the old category is not valid for the new type, default to the empty option
            categorySelect.value = "";
        }
    }

    typeSelect.addEventListener('change', filterCategories);
    
    // Initial filter on page load
    filterCategories();
});
</script>
@endsection