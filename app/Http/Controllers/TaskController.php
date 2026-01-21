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
        // バリデーション
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        // 保存（$request->all() よりも $validated を使う方が安全です）
        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', '新しいタスクを追加しました！');
    }

    // 編集画面の表示
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }   

    // データの更新
    public function update(Request $request, Task $task)
    {
        // バリデーション
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
    ]);

        // 更新実行
        $task->update($validated);

        // 一覧へ戻る
        return redirect()->route('tasks.index')->with('success', 'タスクを更新しました！');
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