<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTag
 */
class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_tags');
    }
}