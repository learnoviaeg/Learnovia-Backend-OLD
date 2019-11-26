<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserGrade;
use App\GradeCategory;
use App\CourseSegment;
use App\GradeItems;
class UserGradeController extends Controller
{
    /**
     * create User grade
     * 
     * @param  [int] grade_item_id, user_id, raw_grade, raw_grade_max, raw_grade_min, raw_scale_id, final_grade, letter_id
     * @param  [boolean] hidden, locked
     * @param  [string] feedback
     * @return [object] and [string] User Grade Created Successfully
    */
    public function create(Request $request)
    {
        $request->validate([
            'grade_item_id' => 'required|exists:grade_items,id',
            'user_id'=> 'required|exists:users,id',
            'raw_grade' => 'required|numeric|between:0,99.99',
            'raw_grade_max' => 'required|numeric|between:0,99.99',
            'raw_grade_min' => 'nullable|numeric|between:0,99.99',
            'raw_scale_id' => 'required|exists:scales,id',
            'final_grade' => 'required|numeric|between:0,99.99',
            'hidden' => 'nullable|boolean',
            'locked' => 'nullable|boolean',
            'feedback' => 'required|string',
            'letter_id' => 'required|exists:letters,id',
        ]);

        $data=[
            'grade_item_id' => $request->grade_item_id,
            'user_id'=> $request->user_id,
            'raw_grade' => $request->raw_grade,
            'raw_grade_max' =>$request->raw_grade_max,
            'raw_scale_id' => $request->raw_scale_id,
            'final_grade' =>$request->final_grade,
            'feedback' => $request->feedback,
            'letter_id' => $request->letter_id
        ];
        if(isset($request->hidden)) {
            $data['hidden']=$request->hidden;
        }
        if(isset($request->locked)) {
            $data['locked']=$request->locked;
        }
        if(isset($request->raw_grade_min)) {
            $data['raw_grade_min']=$request->raw_grade_min;
        }

        $grade=UserGrade::create($data);

        return HelperController::api_response_format(201,$grade,'User Grade Created Successfully');

    }

    /**
     * update User grade
     * 
     * @param  [int] id, grade_item_id, user_id, raw_grade, raw_grade_max, raw_grade_min, raw_scale_id, final_grade, 
     *              letter_id 
     * @param  [boolean] hidden, locked
     * @param  [string] feedback
     * @return [object] and [string] User Grade updated Successfully
    */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_grades,id',
        ]);

        $grade =UserGrade::find($request->id);

        $request->validate([
            'grade_item_id' => 'required|exists:grade_items,id',
            'user_id'=> 'required|exists:users,id',
            'raw_grade' => 'required|numeric|between:0,99.99',
            'raw_grade_max' => 'required|numeric|between:0,99.99',
            'raw_grade_min' => 'nullable|numeric|between:0,99.99',
            'raw_scale_id' => 'required|exists:scales,id',
            'final_grade' => 'required|numeric|between:0,99.99',
            'hidden' => 'nullable|boolean',
            'locked' => 'nullable|boolean',
            'feedback' => 'required|string',
            'letter_id' => 'required|exists:letters,id',
        ]);

        $data=[
            'grade_item_id' => $request->grade_item_id,
            'user_id'=> $request->user_id,
            'raw_grade' => $request->raw_grade,
            'raw_grade_max' =>$request->raw_grade_max,
            'raw_scale_id' => $request->raw_scale_id,
            'final_grade' =>$request->final_grade,
            'feedback' => $request->feedback,
            'letter_id' => $request->letter_id
        ];
        if(isset($request->hidden)) {
            $data['hidden']=$request->hidden;
        }
        if(isset($request->locked)) {
            $data['locked']=$request->locked;
        }
        if(isset($request->raw_grade_min)) {
            $data['raw_grade_min']=$request->raw_grade_min;
        }

        $update=$grade->update($data);

        return HelperController::api_response_format(200, $grade, 'User Grade Updated Successfully');
    }

    /**
     * delete User grade
     * 
     * @param  [int] id
     * @return [string] User Grade deleted Successfully
    */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_grades,id',
        ]);

        $grade = UserGrade::find($request->id);
        $grade->delete();

        return HelperController::api_response_format(201, null, 'User Grade Deleted Successfully');
    }

    /**
     * list User grades
     * 
     * @return [objects] grades
    */
    public function list()
    {
        $grade = UserGrade::all();
        return HelperController::api_response_format(200, $grade);
    }

    public function SingleUserInSingleCourse(Request $request)
    {
        $request->validate([
            'user_id'=> 'required|exists:users,id',
            'course' => 'required|exists:courses,id',
            'class' => 'required|exists:classes,id'
        ]);

        $courseSeg=CourseSegment::GetWithClassAndCourse($request->class,$request->course);
        if(!$courseSeg)
            return HelperController::api_response_format(201, 'this course haven\'t course segment'); 
            
        $gradeCat=GradeCategory::where('course_segment_id',$courseSeg->id)->with('GradeItems')->get();
        $gradeitems=$gradeCat->pluck('GradeItems');
        $userGrade=[];
        foreach($gradeitems as $items){
            $temp = UserGrade::where('user_id',$request->user_id)->whereIn('grade_item_id',$items)
                                ->with('GradeItems','GradeItems.GradeCategory')->first();
            if($temp != null)
                $userGrade[]=$temp;
        }   
        return HelperController::api_response_format(201, $userGrade); 
    }

    public function AllUserInCourse(Request $request)
    {
        $request->validate([
            'course' => 'required|exists:courses,id',
            'class' => 'required|exists:classes,id'
        ]);

        $courseSeg=CourseSegment::GetWithClassAndCourse($request->class,$request->course);
        if(!$courseSeg)
            return HelperController::api_response_format(201, 'this course haven\'t course segment'); 

        $gradeCat=GradeCategory::where('course_segment_id',$courseSeg->id)->with('GradeItems')->get();
        $gradeitems=$gradeCat->pluck('GradeItems');
        $userGrade = [];
        foreach($gradeitems as $items)
            foreach($items as $item)
            {
                if(!isset($item))
                    continue;
                $temp = UserGrade::where('grade_item_id',$item->id)->with('GradeItems','GradeItems.GradeCategory')->get();
                if(count($temp) > 0)
                    $userGrade[]=$temp;
            }
        return HelperController::api_response_format(201, $userGrade);   
    }

    public function AllUserInAllCourses(Request $request)
    {
        $request->validate([
            'year' => 'exists:academic_years,id',
            'type' => 'exists:academic_types,id',
            'level' => 'exists:levels,id|required_with:type',
            'class' => 'exists:classes,id|required_with:level',
        ]);

        $courses_segment=GradeCategoryController::getCourseSegment($request);
        if(isset($courses_segment))
        {
            $gradeCat=GradeCategory::whereIn('course_segment_id',$courses_segment)->with('GradeItems')->get();
            $gradeitems=$gradeCat->pluck('GradeItems');
            $userGrade = [];
            foreach($gradeitems as $items)
                foreach($items as $item)
                {
                    if(!isset($item))
                        continue;
                    $temp = UserGrade::where('grade_item_id',$item->id)->with('GradeItems','GradeItems.GradeCategory')->get();
                    if(count($temp) > 0)
                        $userGrade[]=$temp;
                }
            return HelperController::api_response_format(201, $userGrade);
        }
        return HelperController::api_response_format(200, 'There is No Course segment available.');
    }
}
