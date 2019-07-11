<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Enroll extends Model
{
    protected $fillable = ['user_id' , 'username' , 'course_segment' , 'role_id' ,  'start_date' ,  'end_date'];

   public static function IsExist ($course_segment_id ,$user_id)
   {
    $check = self::where('course_segment',$course_segment_id)->where('user_id',$user_id)->pluck('id')->first();
    if($check == NULL)
    {
        return false;
    }
    return true;

   }

   public static function FindUserbyID ($user_id,$course_segment)
   {
    $check = self::where('user_id',$user_id)->where('course_segment',$course_segment);
    return $check;

   }

   public static function GetCourseSegment ($user_id)
   {
    $check = self::where('user_id', $user_id)->pluck('course_segment');
    return $check;

   }

}
