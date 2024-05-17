<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'contact_email'
    ];

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable')->orderBy('name');
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable')->orderBy('created_at', 'desc');
    }

    public function categories(): MorphMany
    {
        return $this->morphMany(Category::class, 'categorizable')->orderBy('name');
    }
}
