<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_email'
    ];

    /**
     * Get the categories that belong to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the people that belong to the business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    /**
     * Get the tags that belong to the business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the tasks that belong to the business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
