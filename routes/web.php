<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// タスク一覧
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// タスク追加画面
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// タスク編集
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

// タスク削除
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

//完了・未完了の切り替え処理
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');


