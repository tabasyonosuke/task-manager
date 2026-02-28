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

    {{-- タスク作成フォーム --}}
    <form action="{{ route('tasks.store') }}" method="POST" id="task-form" class="mb-10 p-6 bg-white rounded-2xl shadow-sm border border-gray-100 transition-all focus-within:ring-2 focus-within:ring-blue-100">
        @csrf
        <div class="space-y-4">
            <div class="flex flex-col gap-1">
                <input 
                    type="text" 
                    id="task-title-input"
                    name="title" 
                    placeholder="新しいタスクを入力..." 
                    class="flex-1 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors"
                    required
                >
                <p id="title-error-msg" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

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
                        id="submit-button"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-xl font-bold transition-all transform active:scale-95 shadow-md shadow-blue-200 whitespace-nowrap disabled:bg-gray-400 disabled:shadow-none"
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

    {{-- 検索フォーム --}}
    <div class="mb-6">
        <form action="{{ route('tasks.index') }}" method="GET" class="flex gap-2">
            {{-- ソートとフィルターの状態を隠しパラメータで引き継ぐ --}}
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="filter" value="{{ request('filter') }}">
            
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="タスクのタイトルで検索..." 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all"
                >
            </div>
            <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-xl font-medium hover:bg-gray-900 transition-colors text-sm">
                検索
            </button>
            @if(request('search'))
                <a href="{{ route('tasks.index', request()->except('search')) }}" class="flex items-center text-sm text-gray-500 hover:text-gray-700 underline px-2">
                    クリア
                </a>
            @endif
        </form>
    </div>

    {{-- ソート・フィルターメニュー --}}
    <div class="flex flex-wrap justify-between items-center mb-8 gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-2">表示:</span>
            <a href="{{ route('tasks.index', ['sort' => request('sort'), 'search' => request('search')]) }}" 
               class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all {{ !request('filter') ? 'bg-blue-600 text-white shadow-md shadow-blue-100' : 'text-gray-500 hover:bg-gray-100' }}">
               すべて
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'incomplete', 'sort' => request('sort'), 'search' => request('search')]) }}" 
               class="px-4 py-1.5 rounded-xl text-sm font-medium transition-all {{ request('filter') === 'incomplete' ? 'bg-blue-600 text-white shadow-md shadow-blue-100' : 'text-gray-500 hover:bg-gray-100' }}">
               未完了のみ
            </a>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">並び替え:</span>
            <div class="flex bg-gray-100 p-1 rounded-xl">
                <a href="{{ route('tasks.index', ['filter' => request('filter'), 'sort' => 'created_at', 'search' => request('search')]) }}" 
                   class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ request('sort') !== 'due_date' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                   作成順
                </a>
                <a href="{{ route('tasks.index', ['filter' => request('filter'), 'sort' => 'due_date', 'search' => request('search')]) }}" 
                   class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ request('sort') === 'due_date' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                   期限順
                </a>
            </div>
        </div>
    </div>

    {{-- 未完了タスク --}}
    <section class="mb-10">
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">未完了 - {{ $incompleteTasks->count() }}</h2>

        @if($incompleteTasks->isEmpty())
            <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-gray-400">表示できる未完了タスクはありません。</p>
            </div>
        @else
            <ul class="space-y-3">
                @foreach($incompleteTasks as $task)
                    <li class="group flex items-center gap-4 p-4 bg-white border border-gray-100 rounded-2xl hover:border-blue-200 hover:shadow-lg hover:shadow-gray-100 transition-all">
                        {{-- Ajax対応チェックボックス --}}
                        <input 
                            type="checkbox" 
                            class="task-toggle-checkbox w-6 h-6 rounded-full border-2 border-gray-300 focus:ring-0 cursor-pointer transition-colors"
                            data-id="{{ $task->id }}"
                            {{ $task->is_completed ? 'checked' : '' }}
                        >

                        <div class="flex flex-col flex-1 gap-1">
                            <span class="text-gray-700 font-semibold tracking-wide">{{ $task->title }}</span>
                            <div class="flex gap-3 items-center">
                                @if($task->due_date)
                                    <span class="text-xs font-medium text-orange-500 flex items-center gap-1 bg-orange-50 px-2 py-0.5 rounded-full">
                                        📅 {{ $task->due_date->format('n/j') }}
                                    </span>
                                @endif
                                @if($task->description)
                                    <span class="text-xs text-gray-400 line-clamp-1 italic">{{ $task->description }}</span>
                                @endif
                            </div>
                        </div>

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
    @if(!request('filter'))
    <section>
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">完了済み</h2>
        @if($completedTasks->isEmpty())
            <p class="text-gray-400 text-sm ml-1">完了したタスクはまだありません。</p>
        @else
            <ul class="space-y-2 opacity-60">
                @foreach($completedTasks as $task)
                    <li class="flex items-center gap-4 p-3 bg-gray-100 border border-transparent rounded-xl">
                        {{-- Ajax対応チェックボックス (完了済み) --}}
                        <input 
                            type="checkbox" 
                            class="task-toggle-checkbox w-5 h-5 rounded-full border-2 border-gray-400 bg-gray-400 checked:bg-gray-400 focus:ring-0 cursor-pointer"
                            data-id="{{ $task->id }}"
                            checked
                        >
                        <div class="flex flex-col flex-1 gap-1">
                            <span class="text-gray-500 line-through italic">{{ $task->title }}</span>
                        </div>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-300 hover:text-red-400 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
    @endif
@endsection