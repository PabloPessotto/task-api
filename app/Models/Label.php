<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tasks;

class Label extends Model
{
    protected $table = 'label';
    protected $fillable = ['name', 'description', 'color', 'userId'];
    use HasFactory;

    public function tasks()
    {
        return $this->belongsToMany(Tasks::class, 'task_label'); // Use 'task_label' as pivot table name
    }
}
