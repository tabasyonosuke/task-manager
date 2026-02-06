<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // タスク一覧表示
    public function index(Request $request)
    {
        // ログインユーザーのタスククエリを準備
        $query = Auth::user()->tasks();

        // ソート条件の適用
        if ($request->get('sort') === 'due_date') {
            // 期限が近い順
            $query->orderByRaw('due_date IS NULL, due_date ASC');
        } else {
            // 作成が新しい順
            $query->latest();
        }

        // 一旦、現在の並び順でデータを取得
        $allTasks = $query->get();

        // フィルター条件に応じて変数を定義
        if ($request->get('filter') === 'incomplete') {
            // 未完了のみモード
            $incompleteTasks = $allTasks->where('is_completed', false);
            $completedTasks = collect(); // エラー防止のため空のリストを渡す
        } else {
            // すべて表示モード
            $incompleteTasks = $allTasks->where('is_completed', false);
            $completedTasks = $allTasks->where('is_completed', true);
        }

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
            'due_date' => 'nullable|date',
        ]);

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
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

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