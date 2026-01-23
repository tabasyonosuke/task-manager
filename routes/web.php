<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// --- 公開ルート (ログインしていなくてもアクセス可能) ---

// トップページ（未ログインならログイン画面へ、ログイン済ならタスク一覧へ飛ばす設定が一般的ですが、一旦そのままにします）
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// ユーザー登録
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ログイン
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// ログアウト
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- 保護されたルート (ログイン必須) ---

Route::middleware(['auth'])->group(function () {
    // タスク一覧
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

    // タスク追加
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // タスク編集
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

    // タスク削除
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // 完了・未完了の切り替え
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
});