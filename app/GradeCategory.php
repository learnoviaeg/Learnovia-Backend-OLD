<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\IndexScope;
use stdClass;

class GradeCategory extends Model
{
    protected $fillable = ['name', 'course_id', 'parent','index', 'hidden' ,'instance_type' ,'instance_id','lesson_id', 'item_type' , 'type' ,'scale_id',
            'aggregation','weights' , 'min','max' ,'calculation_type' , 'locked','exclude_empty_grades','weight_adjust','reference_category_id','grading_schema_id'];
    
    protected $dispatchesEvents = [
        'created' => \App\Events\CreatedGradeCatEvent::class,
    ];

    public function Child()
    {
        if(isset($GLOBALS['user_id']))
            return $this->hasMany('App\GradeCategory', 'parent', 'id')->where('type' , 'category')->with(['userGrades' => function($query) {
                $query->where("user_id", $GLOBALS['user_id']);
            }]);
        return $this->hasMany('App\GradeCategory', 'parent', 'id')->where('type' , 'category');
    }

    public function child_without_quizzes()
    {
        if(isset($GLOBALS['user_id']))
            return $this->hasMany('App\GradeCategory', 'parent', 'id')->where('type' , 'category')->whereNull('instance_type')->with(['userGrades' => function($query) {
                $query->where("user_id", $GLOBALS['user_id']);
            }]);
        return $this->hasMany('App\GradeCategory', 'parent', 'id')->where('type' , 'category')->whereNull('instance_type');
    }

    public function Children_categories() 
    { 
        return $this->child_without_quizzes()->with(['Children_categories','GradeItems']); 
    }


    public function Parents()
    {
        return $this->hasOne('App\GradeCategory', 'id', 'parent');
    }

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }

    public function GradeItems()
    {
        if(isset($GLOBALS['user_id']))
        return $this->hasMany('App\GradeCategory', 'parent', 'id')->where('type' , 'item')->with(['userGrades' => function($query) {
            $query->where("user_id", $GLOBALS['user_id']);
        }]);
        return $this->hasMany('App\GradeCategory', 'parent', 'id')->where('type' , 'item');
    }

    public function Children() 
    { 
        return $this->Child()->with(['Children','GradeItems']); 
    }

    public function withOutChildren() 
    { 
        return $this->Child(); 
    }

    public function categories_items()
    {
        return $this->hasMany('App\GradeCategory', 'parent', 'id');
    }

    public function schemaCategory()
    {
        return $this->hasMany('App\GradeCategory', 'reference_category_id', 'id');
    }

    public function userGrades()
    {
        return $this->hasMany('App\UserGrader', 'item_id', 'id')->where('item_type','category');
    }

    public function getCalculationTypeAttribute($value)
    {
        $content= json_decode($value);
        return $content;
    }

    public function getWeightsAttribute($value)
    {
        $content= round($value , 2) ;
        return $content;
    }

    public function scale()
    {
        return $this->belongsTo('App\scale', 'scale_id', 'id');
    }

    public static function boot() 
    {
        parent::boot();
        static::addGlobalScope(new IndexScope);
    }
}
