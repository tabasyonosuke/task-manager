@extends('layouts.app')

@section('title', 'タスク管理')

@section('content')
<div class="mb-10 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">My Tasks</h1>
        <p class="text-gray-500">今日も一日、計画的に進めましょう。</p>
    </div>
    <a href="{{ route('tasks.list') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl text-sm font-bold transition-all shadow-sm border border-blue-100 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        タスク一覧
    </a>
</div>

    {{-- 通知系 --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm animate-fade-in text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if (session('share_error'))
        <div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-400 text-orange-700 shadow-sm text-sm font-medium">
            {{ session('share_error') }}
        </div>
    @endif

    {{-- タスク作成フォーム --}}
    <form action="{{ route('tasks.store') }}" method="POST" id="task-form" class="mb-12 p-6 bg-white rounded-3xl shadow-sm border border-gray-100 transition-all focus-within:ring-2 focus-within:ring-blue-100/50">
        @csrf
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- 左側：メイン入力 --}}
            <div class="flex-1 space-y-4">
                <div class="flex flex-col gap-1">
                    <input 
                        type="text" 
                        name="title" 
                        id="task-title-input"
                        placeholder="新しいタスクを入力..." 
                        class="w-full text-2xl font-bold border-none focus:ring-0 placeholder-gray-200 p-0 text-gray-800"
                        required
                    >
                    <p id="title-error-msg" class="text-red-500 text-xs mt-1 hidden"></p>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <textarea 
                    name="description" 
                    placeholder="詳細情報を追加（任意）" 
                    rows="2"
                    class="w-full border-none focus:ring-0 text-sm text-gray-500 p-0 resize-none placeholder-gray-300"
                ></textarea>
            </div>

            {{-- 右側：設定オプション --}}
            <div class="w-full lg:w-72 space-y-5 lg:pl-8 lg:border-l lg:border-gray-50">
                {{-- 期限設定 --}}
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        期限
                    </label>
                    <input type="date" name="due_date" class="w-full border-gray-100 rounded-xl text-sm focus:border-blue-400 focus:ring-0 bg-gray-50/50 transition-all text-gray-500">
                </div>

                {{-- 共有設定（行追加ボタン形式） --}}
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        共有相手
                    </label>
                    
                    {{-- 入力行が追加されるコンテナ --}}
                    <div id="share-emails-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="email" name="share_emails[]" placeholder="メールアドレス" class="flex-1 border-gray-100 rounded-xl text-sm focus:border-blue-400 focus:ring-0 bg-gray-50/50 transition-all font-medium placeholder-gray-300">
                        </div>
                    </div>

                    {{-- 追加ボタン --}}
                    <button type="button" id="add-email-btn" class="mt-2 inline-flex items-center gap-1 text-[10px] font-bold text-blue-500 hover:text-blue-700 transition-colors uppercase tracking-wider ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        共有相手を追加
                    </button>
                </div>

                {{-- 送信ボタン --}}
                <div class="pt-2">
                    <button type="submit" id="submit-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-all transform active:scale-[0.98] shadow-lg shadow-blue-200 flex items-center justify-center">
                        タスクを追加
                    </button>
                </div>
            </div>

        </div>
    </form>

    {{-- 検索・フィルタリング --}}
    <div class="mb-6">
        <form action="{{ route('tasks.index') }}" method="GET" class="flex gap-2">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="filter" value="{{ request('filter') }}">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="タスクを検索..." class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-2xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-50 transition-all">
            </div>
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-2xl text-sm font-bold hover:bg-gray-900 transition-colors shadow-sm tracking-wide">検索</button>
            @if(request('search'))
                <a href="{{ route('tasks.index', request()->except('search')) }}" class="flex items-center text-xs text-gray-400 hover:text-gray-600 underline px-2">クリア</a>
            @endif
        </form>
    </div>

    {{-- 表示切替バー --}}
    <div class="flex flex-wrap justify-between items-center mb-8 p-1.5 bg-gray-100/50 rounded-2xl">
        <div class="flex gap-1">
            <a href="{{ route('tasks.index', ['sort' => request('sort'), 'search' => request('search')]) }}" class="px-5 py-2 rounded-xl text-xs font-bold transition-all {{ !request('filter') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">すべて</a>
            <a href="{{ route('tasks.index', ['filter' => 'incomplete', 'sort' => request('sort'), 'search' => request('search')]) }}" class="px-5 py-2 rounded-xl text-xs font-bold transition-all {{ request('filter') === 'incomplete' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">未完了</a>
        </div>
        <div class="flex gap-1 pr-1">
            <a href="{{ route('tasks.index', ['filter' => request('filter'), 'sort' => 'created_at', 'search' => request('search')]) }}" class="p-2 rounded-lg {{ request('sort') !== 'due_date' ? 'text-blue-600' : 'text-gray-300' }}" title="作成順">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </a>
            <a href="{{ route('tasks.index', ['filter' => request('filter'), 'sort' => 'due_date', 'search' => request('search')]) }}" class="p-2 rounded-lg {{ request('sort') === 'due_date' ? 'text-blue-600' : 'text-gray-300' }}" title="期限順">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </a>
        </div>
    </div>

    {{-- 未完了タスク --}}
    <section class="mb-10">
        <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-5 ml-1 flex items-center gap-2">
            未完了 <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> {{ $incompleteTasks->count() }}
        </h2>
        @if($incompleteTasks->isEmpty())
            <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-gray-100">
                <p class="text-gray-300 text-sm">未完了のタスクはありません。</p>
            </div>
        @else
            <ul class="space-y-4">
                @foreach($incompleteTasks as $task)
                    <li class="group flex items-center gap-4 p-5 bg-white border border-gray-50 rounded-3xl hover:shadow-xl hover:shadow-gray-100/50 transition-all transform hover:-translate-y-0.5">
                        <input type="checkbox" class="task-toggle-checkbox w-6 h-6 rounded-full border-2 border-gray-200 text-blue-500 focus:ring-0 cursor-pointer transition-all" data-id="{{ $task->id }}" {{ $task->is_completed ? 'checked' : '' }}>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-gray-800 font-bold truncate tracking-tight">{{ $task->title }}</span>
                                @if($task->user_id !== Auth::id())
                                    <span class="text-[9px] bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded-md font-black uppercase tracking-tighter">共有済み</span>
                                @elseif($task->sharedUsers->count() > 0)
                                    <span class="text-[9px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-md font-black uppercase tracking-tighter">公開中</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                @if($task->due_date)
                                    <span class="text-[10px] font-bold text-orange-400 flex items-center gap-1 uppercase tracking-tighter">📅 {{ $task->due_date->format('Y年m月d日') }}</span>
                                @endif
                                @if($task->description)
                                    <span class="text-[10px] text-gray-300 italic truncate">{{ $task->description }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-gray-300 hover:text-blue-500 hover:bg-blue-50 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                            @if($task->user_id === Auth::id())
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- 完了済みタスク --}}
    @if(!request('filter'))
    <section>
        <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-5 ml-1 flex items-center gap-2">完了済み</h2>
        @if($completedTasks->isEmpty())
            <p class="text-gray-300 text-xs ml-1">完了したタスクはまだありません。</p>
        @else
            <ul class="space-y-3 opacity-60">
                @foreach($completedTasks as $task)
                    <li class="flex items-center gap-4 p-4 bg-gray-50/50 border border-transparent rounded-2xl">
                        <input type="checkbox" class="task-toggle-checkbox w-5 h-5 rounded-full border-2 border-gray-300 bg-gray-300 checked:bg-gray-400 focus:ring-0 cursor-pointer" data-id="{{ $task->id }}" checked>
                        <div class="flex-1 min-w-0">
                            <span class="text-gray-500 line-through text-sm italic">{{ $task->title }}</span>
                        </div>
                        @if($task->user_id === Auth::id())
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
    @endif
@endsection