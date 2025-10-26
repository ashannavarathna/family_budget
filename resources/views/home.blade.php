<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>家計簿へようこそ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <div class="flex items-center justify-center min-h-screen p-4 sm:p-10">
        <div class="bg-white p-6 sm:p-10 rounded-lg shadow-md max-w-lg text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">
                家計簿へようこそ
            </h1>
            <p class="text-base sm:text-lg text-gray-600 mb-8">
                家計を管理し、収入と支出を追跡し、家計目標を達成しましょう。
            </p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                <a href="login" class="flex-1 bg-blue-600 text-white text-lg font-semibold py-4 px-8 rounded-lg shadow-md transition-colors duration-300 hover:bg-blue-700">
                    ログイン
                </a>
                <a href="register" class="flex-1 bg-green-500 text-white text-lg font-semibold py-4 px-8 rounded-lg shadow-md transition-colors duration-300 hover:bg-green-600">
                    新規登録
                </a>
            </div>
        </div>
    </div>

</body>
</html>