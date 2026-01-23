<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // user_idカラムを追加し、usersテーブルのidと紐付ける
            // constrained()...usersテーブルを参照する制約
            // onDelete('cascade')...ユーザーが消えたらその人のタスクも自動消去
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // ロールバック（元に戻す）時の処理
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};