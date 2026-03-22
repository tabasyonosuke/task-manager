<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //アクセス可能なタスク（作成＋共有）を取得するクエリ
    private function getAccessibleTasksQuery()
    {
        $userId = Auth::id();
        return Task::where('user_id', $userId)
            ->orWhereHas('sharedUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
    }

    // タスク管理（メイン）
    public function index(Request $request)
    {
        $query = $this->getAccessibleTasksQuery();

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->get('sort') === 'due_date') {
            $query->orderByRaw('due_date IS NULL, due_date ASC');
        } else {
            $query->latest();
        }

        $allTasks = $query->get();

        $incompleteTasks = $allTasks->where('is_completed', false);
        $completedTasks = ($request->get('filter') === 'incomplete') ? collect() : $allTasks->where('is_completed', true);

        return view('tasks.index', compact('incompleteTasks', 'completedTasks'));
    }

    // タスク一覧ページ（表示専用）
    public function ListView(Request $request)
    {
        $query = $this->getAccessibleTasksQuery();

        $incompleteTasks = (clone $query)->where('is_completed', false)
                                         ->orderByRaw('due_date IS NULL, due_date ASC')
                                         ->get();

        $completedTasks = (clone $query)->where('is_completed', true)
                                        ->latest('updated_at')
                                        ->get();

        return view('tasks.list', compact('incompleteTasks', 'completedTasks'));
    }

public function store(Request $request)
{
    // バリデーション（email単体チェックから文字列チェックに変更）
    $request->validate([
        'title' => 'required|max:255',
        'share_email' => 'nullable|string', 
    ]);

    // 1. タスクを作成
    $task = Auth::user()->tasks()->create($request->only(['title', 'description', 'due_date']));

    // 2. 共有相手の処理
    if ($request->filled('share_email')) {
        // カンマ、セミコロン、全角カンマ、スペースなどで分割
        $emails = preg_split('/[\s,、;]+/', $request->share_email, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($emails as $email) {
            $user = \App\Models\User::where('email', $email)->first();
            if ($user && $user->id !== Auth::id()) {
                // 既にsharedUsersリレーション（多対多）がある前提
                $task->sharedUsers()->attach($user->id);
            }
        }
    }

    return redirect()->route('tasks.index')->with('success', 'タスクを作成しました。');
}
    public function edit(Task $task)
    {
        $this->authorizeAccess($task);
        return view('tasks.edit', compact('task'));
    }   

    public function update(Request $request, Task $task)
    {
        $this->authorizeAccess($task);
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);
        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'タスクを更新しました！');
    }

    public function destroy(Task $task)
    {
        // 削除はオーナーのみ
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }
        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function toggleComplete(Task $task)
    {
        $this->authorizeAccess($task);
        $task->update(['is_completed' => !$task->is_completed]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'id' => $task->id, 'is_completed' => $task->is_completed]);
        }
        return redirect()->route('tasks.index');
    }

    //タスクを他のユーザーに共有する
    public function share(Request $request, Task $task)
    {
        // オーナーチェック
        if ($task->user_id !== Auth::id()) { abort(403); }

        $request->validate(['email' => 'required|email']);
        $userToShare = User::where('email', $request->email)->first();

        // ユーザーが存在しない
        if (!$userToShare) {
            return back()->with('share_error', '指定されたメールアドレスのユーザーが見つかりません。');
        }

        // 自分自身に共有しようとしている
        if ($userToShare->id === Auth::id()) {
            return back()->with('share_error', '自分自身に共有することはできません。');
        }

        // すでに共有済みかチェック
        if ($task->sharedUsers()->where('user_id', $userToShare->id)->exists()) {
            return back()->with('share_error', 'このユーザーには既に共有されています。');
        }

        // 中間テーブルに保存 (attach)
        $task->sharedUsers()->attach($userToShare->id);

        return back()->with('success', "{$userToShare->name}さんにタスクを共有しました！");
    }

    //共有を解除
    public function unshare(Task $task, User $user)
    {
        if ($task->user_id !== Auth::id()) { abort(403); }

        $task->sharedUsers()->detach($user->id);

        return back()->with('success', '共有を解除しました。');
    }

    // アクセス権限のチェック
    private function authorizeAccess(Task $task)
    {
        $isOwner = $task->user_id === Auth::id();
        $isShared = $task->sharedUsers()->where('user_id', Auth::id())->exists();

        if (!$isOwner && !$isShared) {
            abort(403, 'アクセス権限がありません');
        }
    }
}