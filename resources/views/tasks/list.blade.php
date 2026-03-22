@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Task List</h1>
            <p class="text-gray-500">現在のタスク状況を確認しましょう。</p>
        </div>
        {{-- タスク管理に戻るボタン --}}
        <a href="{{ route('tasks.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            タスク管理へ戻る
        </a>
    </div>

    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- 未完了タスクセクション --}}
    <section class="mb-10">
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">
            未完了 - {{ $incompleteTasks->count() }}
        </h2>

        @if($incompleteTasks->isEmpty())
            <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-gray-400">現在、取り組むべきタスクはありません。素晴らしい！</p>
            </div>
        @else
            <ul class="space-y-3">
                @foreach($incompleteTasks as $task)
                    <li class="group flex items-center gap-4 p-4 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all">
                        {{-- Ajax対応チェックボックス --}}
                        <input 
                            type="checkbox" 
                            class="task-toggle-checkbox w-6 h-6 rounded-full border-2 border-gray-300 focus:ring-0 cursor-pointer transition-colors"
                            data-id="{{ $task->id }}"
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

                        {{-- 編集・削除ボタンもリストで見れるように残しています（不要なら削除可能） --}}
                        <div class="flex items-center gap-1">
                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-gray-400 hover:text-blue-500 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- 完了タスクセクション --}}
    <section>
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">完了済み</h2>
        @if($completedTasks->isEmpty())
            <p class="text-gray-400 text-sm ml-1">完了したタスクはまだありません。</p>
        @else
            <ul class="space-y-2 opacity-60">
                @foreach($completedTasks as $task)
                    <li class="flex items-center gap-4 p-3 bg-gray-100 border border-transparent rounded-xl">
                        <input 
                            type="checkbox" 
                            class="task-toggle-checkbox w-5 h-5 rounded-full border-2 border-gray-400 bg-gray-400 checked:bg-gray-400 focus:ring-0 cursor-pointer"
                            data-id="{{ $task->id }}"
                            checked
                        >
                        <div class="flex flex-col flex-1 gap-1">
                            <span class="text-gray-500 line-through italic">{{ $task->title }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection