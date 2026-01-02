@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <h1 class="text-2xl font-bold mb-4">タスク一覧</h1>

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
                </li>
            @endforeach
        </ul>
    @endif
@endsection