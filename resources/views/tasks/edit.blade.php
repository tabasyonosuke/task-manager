@extends('layouts.app')

@section('title', 'タスクの編集')

@section('content')
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Edit Task</h1>
        <p class="text-gray-500">タスクの詳細を修正して、計画を最新に保ちましょう。</p>
    </div>

    <form action="{{ route('tasks.update', $task) }}" method="POST" class="p-8 bg-white rounded-2xl shadow-sm border border-gray-100 space-y-6">
        @csrf
        @method('PUT')

        {{-- タスク名 --}}
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

        {{-- 詳細 --}}
        <div>
            <label for="description" class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">詳細内容</label>
            <textarea 
                name="description" 
                id="description" 
                rows="4"
                placeholder="具体的な手順やメモをここに..."
                class="w-full border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors"
            >{{ old('description', $task->description) }}</textarea>
        </div>

        {{-- 期限 --}}
        <div>
            <label for="due_date" class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">期限</label>
            <input 
                type="date" 
                name="due_date" 
                id="due_date"
                value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" 
                class="w-full border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors text-gray-600"
            >
            @error('due_date')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-all shadow-md shadow-blue-100 transform active:scale-95">
                更新を保存する
            </button>
            <a href="{{ route('tasks.index') }}" class="px-8 py-3 text-gray-500 font-bold hover:text-gray-700 transition-colors text-center">
                キャンセル
            </a>
        </div>
    </form>
@endsection