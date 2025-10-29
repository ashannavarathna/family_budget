<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>家計簿 - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile menu button -->
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/" class="text-xl font-bold text-gray-800">家計簿</a>
                    </div>
                    <!-- Mobile menu button -->
                    <div class="sm:hidden ml-4">
                        <button type="button" class="mobile-menu-button bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Desktop menu -->
                <div class="hidden sm:flex sm:items-center">
                    @auth
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="/dashboard" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">ダッシュボード</a>
                        <a href="/transactions" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">取引</a>
                        @if(auth()->user()->isAdmin())
                        <a href="/admin/dashboard" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">管理</a>
                        @endif
                    </div>
                    @endauth
                </div>

                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                    <span class="text-gray-700 mr-4 hidden md:inline">ようこそ, {{ auth()->user()->name }}</span>様
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">ログアウト</button>
                    </form>
                    @else
                    <a href="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">ログイン</a>
                    <a href="/register" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium ml-4">登録</a>
                    @endauth
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="mobile-menu hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    @auth
                    <a href="/dashboard" class="bg-blue-50 border-blue-500 text-blue-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">ダッシュボード</a>
                    <a href="/transactions" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">取引</a>
                    @if(auth()->user()->isAdmin())
                    <a href="/admin/dashboard" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">管理</a>
                    @endif
                    <div class="border-t border-gray-200 pt-4 pb-3">
                        <div class="flex items-center px-4">
                            <div class="text-base font-medium text-gray-800">Welcome, {{ auth()->user()->name }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">ログアウト</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="/login" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">ログイン</a>
                    <a href="/register" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">登録</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    @if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm max-w-xs">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm max-w-xs">
        {{ session('error') }}
    </div>
    @endif

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
    
</body>
</html>