<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>

    {{-- Vite (TS / CSS 読み込み) --}}
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
{{-- bodyタグの部分をこのように変更 --}}
<body class="bg-gray-50 text-gray-800 antialiased font-sans">
    <div class="max-w-2xl mx-auto py-12 px-4">
        {{-- メインコンテンツ --}}
        <main class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            @yield('content')
        </main>
    </div>
</body>
</html>
