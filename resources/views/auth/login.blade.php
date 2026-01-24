@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-8 bg-white rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-6">ログイン</h2>
    
    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf

        {{-- メールアドレス --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                class="w-full border rounded-xl px-4 py-2 {{ $errors->any() ? 'border-red-500' : 'border-gray-200' }}" 
                required autofocus>
        </div>

        {{-- パスワード --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">パスワード</label>
            <input type="password" name="password" 
                class="w-full border rounded-xl px-4 py-2 {{ $errors->any() ? 'border-red-500' : 'border-gray-200' }}" 
                required>
        </div>
        
        {{-- エラーメッセージ表示 --}}
        @if ($errors->any())
            <div class="p-3 bg-red-50 rounded-lg">
                <p class="text-red-500 text-sm font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        {{-- ログイン状態の保持 --}}
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-blue-600">
            <label for="remember" class="ml-2 text-sm text-gray-600">ログイン状態を保存する</label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">
            ログイン
        </button>
    </form>
    
    <p class="mt-4 text-center text-sm text-gray-500">
        アカウントをお持ちでないですか？ <a href="{{ route('register') }}" class="text-blue-600 hover:underline">新規登録</a>
    </p>
</div>
@endsection