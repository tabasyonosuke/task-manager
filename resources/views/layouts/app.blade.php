<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">
    <div class="max-w-2xl mx-auto py-12 px-4">
        
        {{-- 追加: ナビゲーションエリア --}}
        <div class="flex justify-between items-center mb-6 px-2">
            <h1 class="text-xl font-bold text-gray-700">
                <a href="{{ route('tasks.index') }}">Task Manager</a>
            </h1>
            
            <div class="flex items-center gap-4">
                @auth
                    {{-- ログイン中 --}}
                    <span class="text-sm text-gray-500">{{ Auth::user()->name }} さん</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">
                            ログアウト
                        </button>
                    </form>
                @else
                    {{-- 未ログイン中 --}}
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">ログイン</a>
                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline ml-2">新規登録</a>
                @endauth
            </div>
        </div>

        <main class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            @yield('content')
        </main>
    </div>
</body>
</html>