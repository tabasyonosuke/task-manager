<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// --- 公開ルート ---
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- ログイン必須 ---
Route::middleware(['auth'])->group(function () {
    
    // 【重要】特定のURL(list)を先に記述
    Route::get('/tasks/list', [TaskController::class, 'ListView'])->name('tasks.list');

    // タスク管理（メイン・追加）
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // タスク編集・更新・削除・切り替え
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');


    // 共有の追加
    Route::post('/tasks/{task}/share', [TaskController::class, 'share'])->name('tasks.share');
    // 共有の解除- どのユーザーの共有を消すか指定するために {user} を含める
    Route::delete('/tasks/{task}/share/{user}', [TaskController::class, 'unshare'])->name('tasks.unshare');
});