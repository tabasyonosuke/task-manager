@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <h1 class="text-2xl font-bold mb-4">タスク一覧</h1>

    {{-- タスク作成 --}}
    <form action="{{ route('tasks.store') }}" method="POST" class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
        @csrf
        <div class="flex gap-2">
            <input 
                type="text" 
                name="title" 
                placeholder="新しいタスクを入力..." 
                class="flex-1 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-semibold transition shadow-sm"
            >
                追加
            </button>
        </div>
        @error('title')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </form>

    {{-- 未完了タスク --}}
    <h2 class="text-lg font-semibold mb-2">未完了</h2>

    @if($incompleteTasks->isEmpty())
        <p class="text-gray-500 mb-4">未完了のタスクはまだありません。</p>
    @else
        <ul class="space-y-2 mb-6">
            @foreach($incompleteTasks as $task)
                <li class="flex items-center gap-3">
                    <form
                        action="{{route('tasks.toggle',$task)}}"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        <input
                            type="checkbox"
                            onchange="this.form.submit()"
                        >
                    </form>

                    <span >
                        {{ $task->title }}
                    </span>


                    {{-- 削除ボタン --}}
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">削除</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- 完了タスク --}}
    <h2 class="text-lg font-semibold mb-2">完了</h2>

    @if($completedTasks->isEmpty())
        <p class="text-gray-500">完了したタスクはありません。</p>
    @else
        <ul class="space-y-2">
            @foreach($completedTasks as $task)
                <li class="flex items-center gap-3 text-gray-400">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input
                            type="checkbox"
                            onchange="this.form.submit()"
                            checked
                        >
                    </form>

                    <span class="line-through">
                        {{ $task->title }}
                    </span>

                    {{-- 削除ボタン --}}
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">削除</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection