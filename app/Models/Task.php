<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TaskStatus;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'taskable_id',
        'taskable_type'
    ];

    protected $casts = [
        'status' => TaskStatus::class
    ];

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }
}
