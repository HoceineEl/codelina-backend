<?php

namespace App\Models;

use Alaouy\Youtube\Facades\Youtube;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCourse
 */
class Course extends Model
{
    use HasFactory;

    const LEVELS = ['beginner', 'intermediate', 'advanced'];
    const BIGINNER = 'beginner';
    const INTERMEDIATE = 'intermediate';
    const ADVANCED = 'advanced';

    protected $fillable = ['title', 'description', 'price', 'level', 'image', 'intro', 'premium', 'created_by', 'category_id'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function students()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['enrollment_date', 'completion_date'])
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'course_tags');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }
    public function getLessonsNumberAttribute()
    {
        $lessonsNumber = 0;
        foreach ($this->sections as $section) {
            $lessonsNumber += $section->lessons->count();
        }
        return $lessonsNumber;
    }
    public function getRatingAttribute()
    {
        return $this->feedbacks->avg('rating');
    }
    public function getStudentsNumberAttribute()
    {
        return $this->students->count();
    }
    public function getTagsListAttribute()
    {
        return $this->tags->pluck('name');
    }
    public function getDurationAttribute()
    {
        $duration = 0;
        foreach ($this->sections as $section) {
            foreach ($section->lessons as $lesson) {
                $duration += $lesson->duration;
            }
        }
        return $duration;
    }
}
