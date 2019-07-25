<?php

namespace Modules\QuestionBank\Entities;

use Illuminate\Database\Eloquent\Model;

class QuizLesson extends Model
{
    protected $fillable = [
        'quiz_id',
        'lesson_id',
        'opening_time',
        'closing_time',
        'max_attemp',
        'grading_method_id',
        'grade',
        'grade_category_id'
    ];
    protected $table = 'quiz_lessons';
}
