<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
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
        'parent_id',
    ];

    /**
     * Get all of the people that are assigned this tag.
     */
    public function people(): MorphToMany
    {
        return $this->morphedByMany(Person::class, 'taggable');
    }

    /**
     * Get all of the businesses that are assigned this tag.
     */
    public function businesses(): MorphToMany
    {
        return $this->morphedByMany(Business::class, 'taggable');
    }
}
