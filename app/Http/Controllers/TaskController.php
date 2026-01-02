<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // タスク一覧表示
    public function index()
    {
        $incompleteTasks = Task::where('is_completed', false)->get();
        $completedTasks = Task::where('is_completed', true)->get();
        return view('tasks.index', compact('incompleteTasks', 'completedTasks'));
    }

    // タスク作成画面
    public function create()
    {
        return view('tasks.create');
    }

    // DBへ保存
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        Task::create([
            'title' => $request->title,
        ]);

        return redirect()->route('tasks.index');
    }

    // 編集画面
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // 更新処理
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $task->update([
            'title' => $request->title,
        ]);

        return redirect()->route('tasks.index');
    }

    // 削除処理
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }

    // 完了・未完了の切り替え処理
    public function toggleComplete(Task $task)
    {
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return redirect()->route('tasks.index');
    }
}