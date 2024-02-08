<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCourse
 */
class Course extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'price', 'premium', 'created_by'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
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
}