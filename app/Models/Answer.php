<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'content',
        'type',
        'question_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function results()
    {
         return $this->hasMany(Result::class);
    }

    public function getImageAttribute()
    {
        if (empty($this->attributes['image'])) {
            return null;
        }

        $answerImgUrl = $this->attributes['image'];

        return asset(config('settings.answer_img_url') . $answerImgUrl);
    }
}
