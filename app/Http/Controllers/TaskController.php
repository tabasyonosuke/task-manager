<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // タスク一覧表示
    public function index()
    {
        // ログイン中のユーザーのタスクだけを取得
        $tasks = Auth::user()->tasks()->latest()->get();

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
            'due_date' => 'nullable|date',
    ]);

    // ログイン中のユーザーに紐付けてタスクを作成
    Auth::user()->tasks()->create([
        'title' => $request->title,
        'description' => $request->description,
        'due_date' => $request->due_date,
        'is_completed' => false,
    ]);

    return redirect()->route('tasks.index');
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