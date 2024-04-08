<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLabel extends Model
{
    protected $table = 'task_label';
    protected $fillable = ['task_id', 'label_id'];
    use HasFactory;
}
