@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <h1 class="text-2xl font-bold mb-4">タスク一覧</h1>

    @if($tasks->isEmpty())
        <p class="text-gray-500">タスクはまだありません。</p>
    @else
        <ul class="space-y-2">
            @foreach($tasks as $task)
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
                            {{ $task->is_completed ? 'checked' : '' }}
                        >
                    </form>

                    <span class="{{ $task->is_completed ? 'line-through text-gray-400' : '' }}">
                        {{ $task->title }}
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
@endsection