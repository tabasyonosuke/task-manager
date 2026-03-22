@extends('layouts.app')

@section('title', 'タスクの編集')

@section('content')
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Edit Task</h1>
        <p class="text-gray-500">タスクの詳細を修正して、計画を最新に保ちましょう。</p>
    </div>

    <div class="space-y-8">
        {{-- メインの編集フォーム --}}
        <form action="{{ route('tasks.update', $task) }}" method="POST" class="p-8 bg-white rounded-2xl shadow-sm border border-gray-100 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">タスク名</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title"
                    value="{{ old('title', $task->title) }}" 
                    class="w-full border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors"
                    required
                >
                @error('title')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">詳細内容</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4"
                    class="w-full border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors"
                >{{ old('description', $task->description) }}</textarea>
            </div>

            <div>
                <label for="due_date" class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">期限</label>
                <input 
                    type="date" 
                    name="due_date" 
                    id="due_date"
                    value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" 
                    class="w-full border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors text-gray-600"
                >
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-50 mt-6">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-all shadow-md shadow-blue-100 transform active:scale-95">
                    更新を保存する
                </button>
                <a href="{{ route('tasks.index') }}" class="px-8 py-3 text-gray-500 font-bold hover:text-gray-700 transition-colors text-center">
                    キャンセル
                </a>
            </div>
        </form>

        {{-- 【追加】共有設定セクション（オーナーのみ表示） --}}
        @if($task->user_id === Auth::id())
        <div class="p-8 bg-gray-50 rounded-2xl border border-gray-200">
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">🤝 共有設定</h2>

            {{-- 共有ユーザー追加フォーム --}}
            <form action="{{ route('tasks.share', $task) }}" method="POST" class="mb-8">
                @csrf
                <div class="flex flex-col sm:flex-row gap-2">
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="共有相手のメールアドレスを入力..." 
                        class="flex-1 border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 transition-colors"
                        required
                    >
                    <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-black transition-colors">
                        追加
                    </button>
                </div>
                @if(session('share_error'))
                    <p class="text-red-500 text-xs mt-2 ml-1">{{ session('share_error') }}</p>
                @endif
            </form>

            {{-- 共有中ユーザーリスト --}}
            <div class="space-y-3">
                <p class="text-xs font-bold text-gray-500 mb-2">共有中のメンバー:</p>
                @forelse($task->sharedUsers as $user)
                    <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-700">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <form action="{{ route('tasks.unshare', [$task, $user]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-bold px-2 py-1">
                                解除
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 italic">共有されているユーザーはいません。</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
@endsection