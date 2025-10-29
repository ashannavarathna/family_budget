@extends('layouts.app')

@section('title', 'アドミン ダッシュボード')

@section('content')
<div class="min-h-screen bg-gray-100">

    <!-- 管理リンク -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- 🔹 Top Navigation Bar -->
        <nav class="bg-white shadow-sm mt-6 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Left side (optional: logo or title) -->
                    <div class="flex-shrink-0 pl-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-xl font-semibold text-gray-800">
                            管理メニュー
                        </a>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-8">
                        <a href="/admin/transactiontype" class="text-gray-600 hover:text-blue-600">取引区分管理</a>
                        <a href="/admin/category" class="text-gray-600 hover:text-blue-600">勘定科目管理</a>
                        <a href="" class="text-gray-600 hover:text-blue-600">ユーザー管理</a>
                        <a href="" class="text-gray-600 hover:text-blue-600">設定</a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center pr-4">
                        <button id="mobile-menu-toggle" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                            <svg id="menu-open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg id="menu-close" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
                <div class="px-4 py-3 space-y-2">
                    <a href="/admin/transactiontype" class="block text-gray-700 hover:text-blue-600">取引区分管理</a>
                    <a href="/admin/category" class="block text-gray-700 hover:text-blue-600">勘定科目管理</a>
                    <a href="" class="block text-gray-700 hover:text-blue-600">ユーザー管理</a>
                    <a href="" class="block text-gray-700 hover:text-blue-600">設定</a>
                </div>
            </div>
            
        </nav>

    </div>

    <!-- 🔹 Main Dashboard Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">勘定科目管理</h1>


            <!-- 取引区分リスト -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">勘定科目リスト</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">取引区分</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">名前</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->transaction_type->name}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->name}}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium sm:px-6">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.category.edit', $category) }}" class="text-green-600 hover:text-green-900">編集</a>
                                            <form action="{{ route('admin.category.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
            </div>
        
        </div>
    </div>
</div>

<!-- 🔹 Mobile Menu Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const openIcon = document.getElementById('menu-open');
    const closeIcon = document.getElementById('menu-close');

    toggle.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        openIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });
});
</script>
@endsection
