<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class UserSurvey extends Model
{
    protected $fillable = ['user_id', 'survey_id'];
}
