<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLesson
 */
class Lesson extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'type', 'section_id'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}