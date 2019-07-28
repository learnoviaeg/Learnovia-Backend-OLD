<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseSegment extends Model
{
    protected $fillable = ['course_id', 'segment_class_id'];


    public static function GetCoursesByCourseSegment($user_id)
    {
        $check = self::where('id', $user_id);
        return $check;
    }

    public static function GetCourseSegmentId($segment_class_id)
    {
        $check = self::where('segment_class_id', $segment_class_id)->pluck('id');
        return $check;
    }

    public static function GetCoursesBysegment_class($user_id)
    {
        $check = self::where('segment_class_id', $user_id);
        return $check;
    }

    public static function getidfromcourse($course_id)
    {
        return self::where('course_id', $course_id)->pluck('id')->first();
    }

    public function courses()
    {
        return $this->hasMany('App\Course', 'id', 'course_id');
    }

    public function segmentClasses()
    {
        return $this->hasMany('App\SegmentClass', 'id', 'segment_class_id');
    }

    public function lessons()
    {
        return $this->hasMany('App\Lesson')->orderBy('index');
    }

    public static function checkRelation($segmentClass, $course)
    {
        $courseSegment = self::whereCourse_id($course)->whereSegment_class_id($segmentClass)->first();
        if ($courseSegment == null) {
            $courseSegment = self::create([
                'course_id' => $course,
                'segment_class_id' => $segmentClass,
            ]);
        }
        return $courseSegment;
    }



    // public static function Get_lessons_from_CourseSegmentID($id){
    //     $CourseSegment=self::where('id',$id)->first();
    //     $lessons=$CourseSegment->courseSegment->lessons;
    //     return $lessons;


    // }

    protected $hidden = [
        'created_at', 'updated_at'
    ];


}
