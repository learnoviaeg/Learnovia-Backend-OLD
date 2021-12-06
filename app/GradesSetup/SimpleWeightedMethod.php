<?php
namespace App\GradesSetup;
use Illuminate\Http\Request;
use App\GradeCategory;

class SimpleWeightedMethod implements GradeSetupInterface
{
    public function calculateMark($grade_category)
    {
        return $grade_category->max;
    }

    public function calculateWeight($grade_category)
    {
        $total_grade = $grade_category->max;
        $total_weight = 100;
        foreach($grade_category->categories_items as $cats)
        {
            if($cats->weight_adjust	 === 1){
                $total_weight -= $cats->weights;
                $total_grade -= $cats->max;
            }
        }
        foreach($grade_category->categories_items as $cats)
        {
            if($cats->weight_adjust	 != 1){
                if($total_grade == 0)
                    $cats->weights =0;
                else
                    $cats->weights = ($cats->max / $total_grade) *$total_weight;
                $cats->save();
            }
        }

    }

    public function calculateUserGrade($user, $grade_category)
    {
        $total_user_mark = 0;
        foreach($grade_category->categories_items as $child){
            $user_mark = userGrader::select('grade')->where('user_id', $user->id)->where('item_id',$child->id)->first();
            $grade_per_item = ($user_mark->grade * $child->weights)/ $child->max;
            $total_user_mark += $grade_per_item;
        }
        $grade = ($total_user_mark) *($grade_category->max/ 100);
        return $grade;
    }

}