<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'is_completed',
        'user_id',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'date',
    ];

    //このタスクを作成したユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function sharedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user')
                    ->withTimestamps();
    }
}