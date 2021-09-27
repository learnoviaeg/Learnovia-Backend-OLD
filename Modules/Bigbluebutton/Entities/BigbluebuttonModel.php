<?php

namespace Modules\Bigbluebutton\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Classes;
use App\Course;

class BigbluebuttonModel extends Model
{
    protected $fillable = ['name','class_id','course_id','attendee_password','moderator_password','duration','meeting_id','started','is_recorded'
    ,'actutal_start_date','status','actual_duration','join_url','type','host_id','signature','user_id'];

    protected $appends = ['class','course'];

    public function getClassAttribute(){
        $class = Classes::find($this->class_id);
        return isset($class)?$class->name:null ;   
    }
            
    public function getCourseAttribute(){
        $course = Course::find($this->course_id);
        return  isset($course)?$course->name:null;
    }

    public function getStatusAttribute(){

        if($this->attributes['status'] == 'past')
            return __('messages.virtual.past');

        if($this->attributes['status'] == 'current')
            return __('messages.virtual.current');

        if($this->attributes['status'] == 'future')
            return __('messages.virtual.future');

    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
