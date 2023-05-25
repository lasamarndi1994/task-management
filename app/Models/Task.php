<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * project is associated with task
     *
     * @return void
     */
    public function projects()
    {
        return $this->belongsTo(Task::class);
    }
}
