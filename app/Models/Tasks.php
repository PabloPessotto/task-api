<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['title', 'description', 'status', 'label', 'userId', 'date', 'order'];
    use HasFactory;

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'task_label');
    }
}
