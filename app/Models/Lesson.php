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
    const TYPES = ['video', 'article', 'quiz'];
    const VIDEO = 'video';
    const ARTICLE = 'article';
    const QUIZ = 'quiz';
    protected $fillable = ['title', 'type', 'url', 'article', 'premium', 'order', 'section_id'];

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