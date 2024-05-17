<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'business_id'
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable')->orderBy('name');
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable')->orderBy('created_at', 'desc');
    }
}
