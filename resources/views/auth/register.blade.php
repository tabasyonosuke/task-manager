@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-8 bg-white rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-6">アカウント作成</h2>

    {{-- 全体のエラー表示 --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">名前</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-200 rounded-xl px-4 py-2 @error('name') border-red-500 @enderror" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-200 rounded-xl px-4 py-2 @error('email') border-red-500 @enderror" required>
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">パスワード</label>
            <input type="password" name="password" class="w-full border-gray-200 rounded-xl px-4 py-2 @error('password') border-red-500 @enderror" required>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">パスワード（確認）</label>
            <input type="password" name="password_confirmation" class="w-full border-gray-200 rounded-xl px-4 py-2" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">
            登録する
        </button>
    </form>
</div>
@endsection