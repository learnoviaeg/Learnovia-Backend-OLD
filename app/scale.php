<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class scale extends Model
{
    public function GradeItems()
    {
        return $this->hasMany('App\GradeItems');
    }
    public function UserGrade()
    {
        return $this->hasMany('App\UserGrade');
    }
}
