<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'task_id', 'user_id'];
    // A comment belongs to a task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    // A comment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}