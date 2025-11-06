@extends('layouts.admin') {{-- NOTE: Ensure this matches your AdminLTE layout file name --}}

@section('title', '取引編集')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>取引編集</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            {{-- Using col-md-8 to limit width, similar to max-w-3xl --}}
            <div class="col-md-8"> 
                {{-- Using card-warning color typically associated with editing/caution --}}
                <div class="card card-warning"> 
                    <div class="card-header">
                        <h3 class="card-title">取引の更新</h3>
                    </div>
                    <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Required for the PUT method --}}
                        
                        <div class="card-body">
                            {{-- Bootstrap row and form-group replace the Tailwind grid and spacing --}}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="transaction_type_id">取引タイプ *</label>
                                    <select name="transaction_type_id" id="transaction_type_id" required
                                            class="form-control @error('transaction_type_id') is-invalid @enderror">
                                        <option value="">タイプ選択</option>
                                        @foreach($transactionTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                {{ old('transaction_type_id', $transaction->transaction_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ ucfirst($type->name) }}
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
                                            <option value="{{ $category->id }}" 
                                                    data-type="{{ $category->transaction_type_id }}"
                                                    {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
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
                                            {{-- NOTE: I corrected the old() check here, as $transaction->user_id looks wrong for account_id --}}
                                            <option value="{{ $account->id }}" 
                                                    {{ old('account_id', $transaction->account_id) == $account->id ? 'selected' : '' }}>
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
                                            value="{{ old('date', $transaction->date->format('Y-m-d')) }}"
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
                                            value="{{ old('party', $transaction->party) }}" placeholder="相手先"
                                            class="form-control @error('party') is-invalid @enderror">
                                    @error('party')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="receipt_number">領収書番号 *</label>
                                    <input type="text" name="receipt_number" id="receipt_number" required 
                                            value="{{ old('receipt_number', $transaction->receipt_number) }}" placeholder="領収書番号"
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
                                            value="{{ old('amount', $transaction->amount) }}" placeholder="0.00"
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
                                                placeholder="任意の備考">{{ old('description', $transaction->description) }}</textarea>
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
                            <button type="submit" class="btn btn-warning float-right">
                                <i class="fas fa-save"></i> 編集
                            </button>
                        </div>
                    </form>
                </div>
                </div>
        </div>
    </div></section>

{{-- The JavaScript below must remain to enable dynamic category filtering. --}}
<script>
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
                // Show the option
                option.style.display = 'block';
            } else {
                // Hide the option
                option.style.display = 'none';
                
                // Unselect if it was selected and is now hidden
                if (option.selected) {
                    option.selected = false;
                }
            }
        });

        // If the current selected value is now hidden, try to reset to the default or force selection
        // This is a browser behavior fix for when the originally selected option is hidden
        if(categorySelect.selectedIndex > -1 && categorySelect.options[categorySelect.selectedIndex].style.display === 'none') {
             categorySelect.value = ''; // Reset selection
        }
    }

    typeSelect.addEventListener('change', filterCategories);
    
    // Initial filter on page load to ensure the pre-selected category is visible
    filterCategories();
});
</script>
@endsection