# Task Manager - 開発メモ

## 2025-12-07

- Laravel × Vite 開発環境構築 (TypeScript / Tailwind)
- migration に tasks テーブル追加
- tasks/index.blade.php 作成
- php artisan serve と npm run dev を同時に起動して動作確認

## 実装済みタスク
- tasks/index.blade.php の実装・修正
- タスク一覧表示機能
- 完了 / 未完了の表示切り替え（UI）
- is_completed カラムの追加（Migration）
- チェックボックスによる状態切り替え機能
- 完了切り替え用ルート（Route）の追加・調整
- Blade の構文エラー修正 (@if($tasks->isEmpty()))
- ローカル環境での動作確認済み
- TaskController に toggleComplete メソッドを追加
- タスク作成機能の実装
- タスク削除機能の実装
- 419エラーおよびルート名の不整合を修正
