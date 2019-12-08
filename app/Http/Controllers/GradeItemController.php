<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GradeItems;
use App\CourseSegment;
use App\GradeCategory;
use App\YearLevel;
use stdClass;


class GradeItemController extends Controller
{
    /**
     * create grade item
     *
     * @param  [int] grade_category, grademin, grademax, item_no, scale_id, aggregationcoef, grade_pass, multifactor,
     *              plusfactor, aggregationcoef2, item_type
     * @param  [boolean] hidden
     * @param  [string] calculation, item_Entity
     * @return [objects] and [message] Grade Created Successfully
    */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'weight' => 'nullable|boolean',
            'grade_category' => 'required|exists:grade_categories,id',
            'grademin' => 'required|integer|min:0',
            'grademax' => 'required|integer|min:grademin',
            'calculation' => 'nullable|string',
            'item_no' => 'nullable|integer',
            'scale_id' => 'nullable|exists:scales,id',
            'grade_pass' => 'nullable|integer',
            'multifactor' => 'nullable|numeric|between:0,99.99',
            'plusfactor' => 'nullable|numeric|between:0,99.99',
            'aggregationcoef' => 'nullable|numeric|between:0,99.99',
            'aggregationcoef2' => 'nullable|numeric|between:0,99.99',
            'item_type' => 'nullable|exists:item_types,id',
            'item_Entity' => 'nullable',
            'hidden' => 'nullable|boolean'
        ]);

        $id_number=GradeCategory::find($request->grade_category);
        // return $id_number;

        $data = [
            'grade_category' => $request->grade_category,
            'grademin' => $request->grademin,
            'grademax' => $request->grademax,
            'calculation' => (isset($request->calculation)) ? $request->calculation : null,
            'item_no' => (isset($request->item_no)) ? $request->item_no : null,
            'scale_id' => (isset($request->scale_id)) ? $request->scale_id : null,
            'grade_pass' => (isset($request->grade_pass)) ? $request->grade_pass : null,
            'aggregationcoef' => (isset($request->aggregationcoef)) ? $request->aggregationcoef : null,
            'aggregationcoef2' => (isset($request->aggregationcoef2)) ? $request->aggregationcoef2 : null,
            'item_type' => (isset($request->item_type)) ? $request->item_type : null,
            'id_number' => $id_number->id_number,
            'item_Entity' => (isset($request->item_Entity)) ? $request->item_Entity : null,
            'hidden' => (isset($request->hidden)) ? $request->hidden : 0,
            'multifactor' => (isset($request->multifactor)) ? $request->multifactor : 1,
            'name' => (isset($request->name)) ? $request->name : 'Grade Item',
            'weight' => (isset($request->weight)) ? $request->weight : 0,
            'plusfactor' => (isset($request->plusfactor)) ? $request->plusfactor : 1,
        ];

        $grade = GradeItems::create($data);

        return HelperController::api_response_format(201, $grade, 'Grade item Created Successfully');
    }

    public function AddBulk(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'string',
            'items.*.weight' => 'boolean',
            'items.*.grade_category' => 'required|exists:grade_categories,name',
            'items.*.grademin' => 'required|integer|min:0',
            'items.*.grademax' => 'required|integer|min:grademin',
            'items.*.calculation' => 'nullable|string',
            'items.*.item_no' => 'nullable|integer',
            'items.*.scale_id' => 'nullable|exists:scales,id',
            'items.*.grade_pass' => 'nullable|integer',
            'items.*.multifactor' => 'numeric|between:0,99.99',
            'items.*.plusfactor' => 'numeric|between:0,99.99',
            'items.*.aggregationcoef' => 'numeric|between:0,99.99',
            'items.*.aggregationcoef2' => 'numeric|between:0,99.99',
            'items.*.item_type' => 'nullable|exists:item_types,id',
            'items.*.item_Entity' => 'nullable',
            'items.*.hidden' => 'boolean',
            'year' => 'exists:academic_years,id',
            'level'=> 'exists:levels,id',
            'type' => 'exists:academic_types,id',
            'segment' => 'exists:segments,id',
            'class' => 'exists:classes,id',
            'courses' => 'array|exists:courses,id'
        ]);

        $jop = (new \App\Jobs\AddGradeItemJob($request->items,GradeCategoryController::getCourseSegment($request)));
        dispatch($jop);
        return HelperController::api_response_format(200, null, 'Grade items are created successfully');
    }

    public function AssignBulk(Request $request)
    {
        $request->validate([
            'item_name' => 'required|exists:grade_items,name',
            'grade_category_name' => 'required|exists:grade_categories,name',
            'year' => 'exists:academic_years,id',
            'type' => 'exists:academic_types,id|required_with:level',
            'level' => 'exists:levels,id|required_with:class',
            'class' => 'exists:classes,id',
            'segment' => 'exists:segments,id',
            'courses' => 'array|exists:courses,id'
        ]);

        $coursesegment=GradeCategoryController::getCourseSegment($request);
        if(!$coursesegment)
            return HelperController::api_response_format(200, 'There is No Course segment available.');
            
        // return $coursesegment;
        foreach($coursesegment as $courseseg)
        {
                // $year_level_tree=CourseSegment::where('id',$courseseg)->with(['segmentClasses.classLevel.yearLevels' => function ($query) use ($request){
                //     $query->pluck('id')->first();
                // }])->get();
                $segclass=CourseSegment::find($courseseg)->segmentClasses;
                $classlevel=$segclass[0]->classLevel;
                $year_level= $classlevel[0]->yearLevels;
                $gradeitem=GradeItems::where('name',$request->item_name)->first();
                $gradecat=GradeCategory::where('name',$request->grade_category_name)->where('course_segment_id',$courseseg)->pluck('id')->first();
                if($gradecat)
                {
                    $grade_category[] = GradeItems::firstOrCreate([
                        'grade_category' => $gradecat,
                        'grademin' => $gradeitem->grademin,
                        'grademax' => $gradeitem->grademax,
                        'calculation' => $gradeitem->calculation,
                        'item_no' => $gradeitem->item_no,
                        'scale_id' => $gradeitem->scale_id,
                        'grade_pass' => $gradeitem->grade_pass,
                        'aggregationcoef' => $gradeitem->aggregationcoef,
                        'aggregationcoef2' => $gradeitem->aggregationcoef2,
                        'item_type' => $gradeitem->item_type,
                        'item_Entity' => $gradeitem->item_Entity,
                        'hidden' => $gradeitem->hidden,
                        'multifactor' => $gradeitem->multifactor,
                        'name' =>  $gradeitem->name ,
                        'weight' => $gradeitem->weight ,
                        'plusfactor' => $gradeitem->plusfactor ,
                        'id_number' =>  $year_level[0]->id
                    ]); 
                }
        }
        return HelperController::api_response_format(200, $grade_category,'Grade Item Assigned.');

    }


    public function deleteBulkGradeitems(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:year_levels,id',
            'name' => 'required|exists:grade_items,name',
        ]);
        $gradeItem = GradeItems::whereNotNull('id_number');
        if($request->filled('id'))
            $gradeItem->where('id_number', $request->id);
        $gradeItem->where('name', $request->name);
        $gradeItem->delete();
        return HelperController::api_response_format(200, null, 'Grade Items is deleted successfully');
    }

    /**
     * update grade item
     *
     * @param  [int] id, grade_category, grademin, grademax, item_no, scale_id, aggregationcoef, grade_pass, multifactor,
     *              plusfactor, aggregationcoef2, item_type
     * @param  [boolean] hidden
     * @param  [string] calculation, item_Entity
     * @param  [boolean] hidden
     * @return [objects] and [message] Grade updated Successfully
    */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:grade_items,id',
        ]);

        $grade = GradeItems::find($request->id);
        $request->validate([
            'grade_category' => 'required|exists:grade_categories,id',
            'grademin' => 'required|integer|min:0',
            'grademax' => 'required|integer|min:grademin',
            'calculation' => 'required|string',
            'item_no' => 'nullable|integer',
            'scale_id' => 'required|exists:scales,id',
            'grade_pass' => 'required|integer',
            'multifactor' => 'nullable|numeric|between:0,99.99',
            'plusfactor' => 'nullable|numeric|between:0,99.99',
            'aggregationcoef' => 'nullable|numeric|between:0,99.99',
            'aggregationcoef2' => 'nullable|numeric|between:0,99.99',
            'item_type' => 'required|exists:item_types,id',
            'item_Entity' => 'required',
            'hidden' => 'nullable|integer'
        ]);

        $data = [
            'grade_category' => $request->grade_category,
            'grademin' => $request->grademin,
            'grademax' => $request->grademax,
            'calculation' => $request->calculation,
            'item_no' => $request->item_no,
            'scale_id' => $request->scale_id,
            'grade_pass' => $request->grade_pass,
            'aggregationcoef' => $request->aggregationcoef,
            'aggregationcoef2' => $request->aggregationcoef2,
            'item_type' => $request->item_type,
            'item_Entity' => $request->item_Entity
        ];
        if (isset($request->multifactor)) {
            $data['multifactor'] = $request->multifactor;
        }
        if (isset($request->plusfactor)) {
            $data['plusfactor'] = $request->plusfactor;
        }
        if (isset($request->hidden)) {
            $data['hidden'] = $request->hidden;
        }

        $update = $grade->update($data);


        return HelperController::api_response_format(200, $grade, 'Grade Updated Successfully');

    }

    /**
     * delete grade item
     *
     * @param  [int] id
     * @return [objects] and [message] Grade deleted Successfully
    */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:grade_items,id',
        ]);

        $grade = GradeItems::find($request->id);
        $grade->delete();

        return HelperController::api_response_format(201, null, 'Grade Deleted Successfully');

    }

    /**
     * list/get grade item
     *
     * @return [objects] all grade items with Grade category and item type and scale
    */
    public function list()
    {
        $grade = GradeItems::with(['GradeCategory', 'ItemType', 'scale'])->get();
        return HelperController::api_response_format(200, $grade);
    }

    public function GetAllGradeItems(Request $request)
    {
        $result = [];
        $gradeItems = GradeItems::whereNotNull('id_number')->get();
        foreach ($gradeItems as $item) {
            if (!isset($result[$item->name])) {
                $result[$item->name] = $item;
                $result[$item->name]->levels = collect();
            }
            $temp = new stdClass();
            $temp->name = YearLevel::find($item->id_number)->levels[0]->name;
            $temp->id = $item->id_number;
            if (!$result[$item->name]->levels->contains($temp))
                $result[$item->name]->levels->push($temp);
        }
        return HelperController::api_response_format(200, $result);
    }

    /**
     * move  grade item to new category
     *
     * @param  [int] id, newcategory
     * @return [objects] and [message] Grade item Category is moved successfully
    */
    public function Move_Category(Request $request){
        $request->validate([
            'id' => 'required|exists:grade_items,id',
            'newcategory' => 'required|exists:grade_categories,id',
        ]);
        $GardeCategory = GradeItems::find($request->id);
        $GardeCategory->update([
            'grade_category' => $request->newcategory,
        ]);
        return HelperController::api_response_format(200, $GardeCategory, 'Grade item Category is moved successfully');

    }

    public function override(Request $request)
    {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'required|exists:grade_items,id',
            'weight' => 'required|array',
            'weight.*' => 'required|min:0|max:100',
        ]);
        $message = null ;
        $gradeCategory = GradeItems::whereIn('id' , $request->id)->groupBy('grade_category')->pluck('grade_category');
        if(count($gradeCategory) != 1)
            return HelperController::api_response_format(400 , null , 'This grade items not belong to the same grade category');
        foreach ($request->id as $index => $id) {
            $grade_item = GradeItems::find($id);
            $grade_item->update(['weight' => round($request->weight[$index] , 3 )]);
        }
        $grade_items = $grade_item->GradeCategory->GradeItems;
        $allWeight = 0;
        foreach ($grade_items as $grade_item) {
            $allWeight += $grade_item->weight();
            $weight[] = $grade_item->weight();
        }
        if ($allWeight != 100) {
            $message = "Your grades adjusted to get 100!";
            $gcd = self::findGCD($weight, sizeof($weight));
            foreach ($weight as $w) {
                $devitions[]= $w/$gcd;
            }
            $calculations=(100/ array_sum($devitions));
            $count=0;
            foreach ($grade_items as $grade_item) {
                $grade_item->update(['weight' =>round($devitions[$count]*$calculations , 3)]);
                $count++;
            }
        }
        return HelperController::api_response_format(200, $grade_items, $message);

    }

    public static function gcd($a, $b)
    {
        if ($a == 0)
            return $b;
        return self::gcd($b % $a, $a);
    }

    public static function findGCD($arr, $n)
    {
        $result = $arr[0];
        for ($i = 1; $i < $n; $i++)
            $result = self::gcd($arr[$i], $result);

        return $result;
    }
    public function gradeing_method()
    {
        return[
            [
                'id' => 1,
                'name' =>'Natural'
            ],
            [
                'id' => 2,
                'name' =>'Simple weighted mean'
            ],
            [
                'id' => 3,
                'name' =>'Weighted mean'
            ]
        ];
}
}