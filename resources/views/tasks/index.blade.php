@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">My Tasks</h1>
        <p class="text-gray-500">今日も一日、計画的に進めましょう。</p>
    </div>

    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    {{-- タスク作成フォーム (拡張版) --}}
    <form action="{{ route('tasks.store') }}" method="POST" class="mb-10 p-6 bg-white rounded-2xl shadow-sm border border-gray-100 transition-all focus-within:ring-2 focus-within:ring-blue-100">
        @csrf
        <div class="space-y-4">
            {{-- 1段目：タイトル --}}
            <div class="flex gap-3">
                <input 
                    type="text" 
                    name="title" 
                    placeholder="新しいタスクを入力..." 
                    class="flex-1 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors"
                    required
                >
            </div>

            {{-- 2段目：詳細と期限 --}}
            <div class="flex flex-col md:flex-row gap-3">
                <textarea 
                    name="description" 
                    placeholder="詳細（任意）" 
                    rows="1"
                    class="flex-1 border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-blue-500 transition-colors"
                ></textarea>
                
                <div class="flex gap-3">
                    <input 
                        type="date" 
                        name="due_date" 
                        class="border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-blue-500 transition-colors text-gray-500"
                    >
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-xl font-bold transition-all transform active:scale-95 shadow-md shadow-blue-200 whitespace-nowrap"
                    >
                        追加
                    </button>
                </div>
            </div>
        </div>
        @error('title')
            <p class="text-red-500 text-sm mt-3 ml-1">{{ $message }}</p>
        @enderror
    </form>

    {{-- 未完了タスク --}}
    <section class="mb-10">
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">未完了 - {{ $incompleteTasks->count() }}</h2>

        @if($incompleteTasks->isEmpty())
            <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-gray-400">現在、取り組むべきタスクはありません。</p>
            </div>
        @else
            <ul class="space-y-3">
                @foreach($incompleteTasks as $task)
                    <li class="group flex items-center gap-4 p-4 bg-white border border-gray-100 rounded-2xl hover:border-blue-200 hover:shadow-lg hover:shadow-gray-100 transition-all">
                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PATCH')
                            <input 
                                type="checkbox" 
                                class="task-toggle-checkbox w-6 h-6 rounded-full border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                            >
                        </form>

                        {{-- タスク名・期限・詳細の表示エリア --}}
                        <div class="flex flex-col flex-1 gap-1">
                            <span class="text-gray-700 font-semibold tracking-wide">{{ $task->title }}</span>
                            
                            <div class="flex gap-3 items-center">
                                @if($task->due_date)
                                    <span class="text-xs font-medium text-orange-500 flex items-center gap-1 bg-orange-50 px-2 py-0.5 rounded-full">
                                        📅 {{ $task->due_date->format('n/j') }}
                                    </span>
                                @endif
                                
                                @if($task->description)
                                    <span class="text-xs text-gray-400 line-clamp-1 italic">
                                        {{ $task->description }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 操作ボタンエリア --}}
                        <div class="flex items-center gap-1">
                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- 完了タスク --}}
    <section>
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">完了済み</h2>

        @if($completedTasks->isEmpty())
            <p class="text-gray-400 text-sm ml-1">完了したタスクはまだありません。</p>
        @else
            <ul class="space-y-2 opacity-60">
                @foreach($completedTasks as $task)
                    <li class="flex items-center gap-4 p-3 bg-gray-100 border border-transparent rounded-xl">
                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PATCH')
                            <input 
                                type="checkbox" 
                                class="task-toggle-checkbox w-5 h-5 rounded-full border-gray-300 text-gray-400 focus:ring-gray-400 cursor-pointer"
                                checked
                            >
                        </form>

                        <div class="flex flex-col flex-1 gap-1">
                            <span class="text-gray-500 line-through italic">{{ $task->title }}</span>
                            
                            <div class="flex gap-3 items-center">
                                @if($task->due_date)
                                    <span class="text-xs font-medium text-gray-400 flex items-center gap-1">
                                        📅 {{ $task->due_date->format('n/j') }}
                                    </span>
                                @endif
                                
                                @if($task->description)
                                    <span class="text-xs text-gray-400 line-clamp-1 italic">
                                        {{ $task->description }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-gray-300 hover:text-blue-400 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>   
                            
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-300 hover:text-red-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection