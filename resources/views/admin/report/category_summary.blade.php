@extends('layouts.admin')

@section('title', '勘定科目別サマリー')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>勘定科目別サマリーレポート</h1>
            </div>
        </div>
    </div></section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                {{-- Card for Filters (Optional - Collapsed by default) --}}
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">期間の絞り込み</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- 💡 ここに期間検索フォームを実装します (例: 開始日/終了日) --}}
                        {{-- フォームのアクションは、このレポートのルート自身 (例: admin.reports.category-summary) に設定し、GETメソッドで送信します --}}
                        <form method="GET" action="{{ route('admin.reports.category-summary') }}">
                            <div class="row">
                                
                                {{-- 1. 取引タイプ (Transaction Type) --}}
                                <div class="col-md-3 form-group">
                                    <label for="transaction_type_id">取引タイプ</label>
                                    <select name="transaction_type_id" id="transaction_type_id" class="form-control">
                                        <option value="">すべて</option>
                                        @foreach($transactionTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                    {{ request('transaction_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 2. 取引カテゴリー (Category) --}}
                                <div class="col-md-3 form-group">
                                    <label for="category_id">取引カテゴリー</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">すべて</option>
                                        @foreach($categoriesList as $category)
                                            {{-- カテゴリーオプションに data-type 属性を追加し、JavaScriptでフィルタリングできるようにします --}}
                                            <option value="{{ $category->id }}" 
                                                    data-type="{{ $category->transaction_type_id }}"
                                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- 3. ユーザー選択 (User Select Box) --}}
                                <div class="col-md-3 form-group">
                                    <label for="user_id">ユーザー</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">すべて</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- 4. 開始日 (Start Date) --}}
                                <div class="col-md-2 form-group">
                                    <label for="start_date">開始日</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" 
                                           value="{{ request('start_date') }}">
                                </div>

                                {{-- 5. 終了日 (End Date) --}}
                                <div class="col-md-2 form-group">
                                    <label for="end_date">終了日</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" 
                                           value="{{ request('end_date') }}">
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-12 text-right">
                                    <a href="{{ route('admin.reports.category-summary') }}" class="btn btn-default">リセット</a>
                                    <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> 検索・絞り込み</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- Card for the Report Table --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">勘定科目別 集計結果</h3>
                    </div>
                    <div class="card-body">
                        <table id="categorySummaryTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 25%">勘定科目</th>
                                    <th style="width: 15%">取引区分</th>
                                    <th class="text-right">取引件数</th>
                                    <th class="text-right">合計金額</th>
                                    <th class="text-right">平均金額</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categorySummary as $summary)
                                <tr>
                                    <td>{{ $summary->category_name }}</td>
                                    <td>
                                        @php
                                            // 取引区分 (type) に基づいてバッジの色を決定
                                            $badgeClass = ($summary->type === '収入') ? 'bg-success' : 'bg-danger';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $summary->type }}
                                        </span>
                                    </td>
                                    {{-- 取引件数 --}}
                                    <td class="text-right">{{ number_format($summary->transaction_count) }} 件</td>
                                    
                                    {{-- 合計金額 (金額は色分け) --}}
                                    <td class="text-right @if($summary->type === '収入') text-success @else text-danger @endif font-weight-bold">
                                        {{ number_format($summary->total_amount) }} 円
                                    </td>
                                    
                                    {{-- 平均金額 --}}
                                    <td class="text-right">
                                        {{ number_format($summary->average_amount, 2) }} 円
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">データがありません。取引を登録してください。</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
        </div>
    </div>
</section>

{{-- 💡 取引タイプに基づいてカテゴリーを動的に絞り込む JavaScript --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('transaction_type_id');
        const categorySelect = document.getElementById('category_id');
        // 全てのカテゴリーオプションをメモリに保持
        const allCategoryOptions = Array.from(categorySelect.options).filter(opt => opt.value !== "");

        function filterCategories() {
            const selectedTypeId = typeSelect.value;
            const currentCategoryId = categorySelect.value;
            
            // カテゴリーセレクトボックスをクリア (デフォルトの「すべて」は残す)
            categorySelect.querySelectorAll('option:not(:first-child)').forEach(option => option.remove());

            let categoryFound = false;

            allCategoryOptions.forEach(option => {
                const optionTypeId = option.getAttribute('data-type');
                
                // 選択されたタイプIDがない（すべて）か、タイプIDが一致する場合
                if (selectedTypeId === '' || optionTypeId === selectedTypeId) {
                    categorySelect.appendChild(option.cloneNode(true));
                    
                    // 以前選択されていたカテゴリーIDが、新しいリストに含まれているかチェック
                    if (option.value === currentCategoryId) {
                        categoryFound = true;
                    }
                }
            });

            // 以前の選択値が新しいリストに含まれている場合は再選択
            if (categoryFound) {
                categorySelect.value = currentCategoryId;
            } else {
                 // 含まれていない場合は、「すべて」を選択状態にする
                categorySelect.value = "";
            }
        }

        // イベントリスナーを設定
        typeSelect.addEventListener('change', filterCategories);
        
        // ページロード時にも一度実行し、初期選択状態を反映
        filterCategories();
    });
</script>
@endpush
@endsection