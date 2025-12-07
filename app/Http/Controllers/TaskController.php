<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // タスク一覧表示
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
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
}
