<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['id','name'];
    public function AC_Type()
    {
        return $this->belongsToMany('App\AcademicType', 'academic_year_types', 'academic_year_id','academic_type_id');
    }
    protected $hidden = [
        'created_at','updated_at','pivot',
    ];
}