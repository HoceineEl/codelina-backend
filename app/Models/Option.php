<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOption
 */
class Option extends Model
{
    use HasFactory;
    protected $fillable = ['question_id', 'order', 'content', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
