<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserGrade;

class UserGradeController extends Controller
{
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



    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_grades,id',
        ]);

        $grade = UserGrade::find($request->id);
        $grade->delete();

        return HelperController::api_response_format(201, null, 'User Grade Deleted Successfully');

    }


    public function list()
    {
        $grade = UserGrade::all();
        return HelperController::api_response_format(200, $grade);
    }

    public function Add(Request $request)
    {
        $request->validate([
            'grade_item_id' => 'required|array',
            'grade_item_id.*' => 'required|exists:grade_items,id',
            'user_id'=> 'required|array',
            'user_id.*'=> 'required|exists:users,id',
            'raw_grade' => 'required|array',
            'raw_grade.*' => 'required|numeric|between:0,99.99',
            'raw_grade_max' => 'required|array',
            'raw_grade_max.*' => 'required|numeric|between:0,99.99',
            'raw_grade_min' => 'nullable|array',
            'raw_grade_min.*' => 'nullable|numeric|between:0,99.99',
            'raw_scale_id' => 'required|array',
            'raw_scale_id.*' => 'required|exists:scales,id',
            'final_grade' => 'required|array',
            'final_grade.*' => 'required|numeric|between:0,99.99',
            'hidden' => 'nullable|array',
            'hidden.*' => 'nullable|boolean',
            'locked' => 'nullable|array',
            'locked.*' => 'nullable|boolean',
            'feedback' => 'required|array',
            'feedback.*' => 'required|string',
            'letter_id' => 'required|array',
            'letter_id.*' => 'required|exists:letters,id',
        ]);
        foreach ($request->user_id as $key => $userid) {
            $data=[
                'grade_item_id' => $request->grade_item_id[$key],
                'user_id'=> $request->user_id[$key],
                'raw_grade' => $request->raw_grade[$key],
                'raw_grade_max' =>$request->raw_grade_max[$key],
                'raw_scale_id' => $request->raw_scale_id[$key],
                'final_grade' =>$request->final_grade[$key],
                'feedback' => $request->feedback[$key],
                'letter_id' => $request->letter_id[$key],
            ];
            if(isset($request->hidden[$key])) {
                $data['hidden']=$request->hidden[$key];
            }
            if(isset($request->locked[$key])) {
                $data['locked']=$request->locked[$key];
            }
            if(isset($request->raw_grade_min[$key])) {
                $data['raw_grade_min']=$request->raw_grade_min[$key];
            }

            $grade=UserGrade::create($data);
        }
        return HelperController::api_response_format(201,'Users Grades are Created Successfully');

    }

}
