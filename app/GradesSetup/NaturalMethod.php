<?php
namespace App\GradesSetup;
use Illuminate\Http\Request;
use App\GradeCategory;

class NaturalMethod implements GradeSetupInterface
{
    public function calculateMark($grade_category)
    {
        $total_category_mark = 0;
        foreach($grade_category->categories_items as $items){
            if(($items->weights === 0 && $category->weight_adjust === 1 ) || $items->parent != $grade_category->id)
                continue;
            $total_category_mark += $items->max;
        }        
        return $total_category_mark;
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

}
