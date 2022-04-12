<?php

namespace App\Http\Controllers;
use App\Repositories\ChainRepositoryInterface;
use App\Enroll;
use App\User;
use Illuminate\Http\Request;
use App\LetterDetails;
use App\ScaleDetails;
use Spatie\Permission\Models\Permission;

class ReportCardsController extends Controller
{
    public function __construct(ChainRepositoryInterface $chain)
    {
        $this->chain = $chain;
        $this->middleware('auth');
        $this->middleware(['permission:report_card/mfis/mfisg|report_card/mfis/mfisb'],   ['only' => ['manaraReport']]);
        $this->middleware(['permission:report_card/mfis/manara-boys/printAll|report_card/mfis/manara-girls/printAll'],   ['only' => ['manaraReportAll']]);
        $this->middleware(['permission:report_card/haramain/all'],   ['only' => ['haramaninReportAll']]);
        $this->middleware(['permission:report_card/forsan/all'],   ['only' => ['forsanReportAll']]);
        $this->middleware(['permission:report_card/fgls/all'],   ['only' => ['fglsReportAll', 'fglsPrep3ReportAll']]);
        $this->middleware(['permission:report_card/mfis/mfisg-monthly|report_card/mfis/mfisb-monthly'],   ['only' => ['manaraMonthlyReport']]);
        $this->middleware(['permission:report_card/mfis/manara-boys/monthly/printAll|report_card/mfis/manara-girls/monthly/printAll'],   ['only' => ['manaraMonthylReportAll']]);
        $this->middleware(['permission:report_card/fgls/final'],   ['only' => ['fglFinalReport']]);
        $this->middleware(['permission:report_card/fgls/all-final'],   ['only' => ['fglsFinalReportAll']]);       
        $this->middleware(['permission:report_card/forsan/monthly'],   ['only' => ['forsanMonthlyReport']]);
        $this->middleware(['permission:report_card/forsan/monthly/printAll'],   ['only' => ['forsanMonthylReportAll']]);
    }

    public function haramainReport(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $allowed_levels=Permission::where('name','report_card/haramain')->pluck('allowed_levels')->first();
        $allowed_levels=json_decode($allowed_levels);

        $student_levels = Enroll::where('user_id',$request->user_id)->pluck('level')->toArray();
        $check=(array_intersect($allowed_levels, $student_levels));

        if(count($check) == 0)
            return response()->json(['message' => 'You are not allowed to see report card', 'body' => null ], 200);
        
        $GLOBALS['user_id'] = $request->user_id;
        $grade_category_callback = function ($qu) use ($request ) {
            $qu->whereNull('parent')
            ->with(['Children.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'GradeItems.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            }]); 
        };

        $course_callback = function ($qu) use ($request ) {
            $qu->Where(function ($query) {
                $query->where('name', 'LIKE' , "%Grades%")
                      ->orWhere('name','LIKE' , "%درجات%");
            });     
        };

        $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
            $qu->where('role_id', 3);
            $qu->whereHas('courses' , $course_callback)
                ->with(['courses' => $course_callback]); 
            $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                ->with(['courses.gradeCategory' => $grade_category_callback]); 
        };

        $result = User::whereId($request->user_id)->whereHas('enroll' , $callback)
                        ->with(['enroll' => $callback , 'enroll.levels' , 'enroll.type'])->first();

        return response()->json(['message' => null, 'body' => $result ], 200);
    }

    public function forsanReport(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $GLOBALS['user_id'] = $request->user_id;

        $allowed_levels=Permission::where('name','report_card/forsan')->pluck('allowed_levels')->first();
        $allowed_levels=json_decode($allowed_levels);

        $student_levels = Enroll::where('user_id',$request->user_id)->pluck('level')->toArray();
        $check=(array_intersect($allowed_levels, $student_levels));

        if(count($check) == 0)
            return response()->json(['message' => 'You are not allowed to see report card', 'body' => null ], 200);

        $grade_category_callback = function ($qu) use ($request ) {
            $qu->whereNull('parent')
            ->with(['Children.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'GradeItems.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            }]); 
        };

        $course_callback = function ($qu) use ($request ) {
            $qu->Where(function ($query) {
                $query->where('name', 'LIKE' , "%Grades%")
                      ->orWhere('name','LIKE' , "%درجات%"); 
            });     
        };

        $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
            $qu->where('role_id', 3);
            $qu->whereHas('courses' , $course_callback)
                ->with(['courses' => $course_callback]); 
            $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                ->with(['courses.gradeCategory' => $grade_category_callback]); 
        };

        $result = User::whereId($request->user_id)->whereHas('enroll' , $callback)
                        ->with(['enroll' => $callback , 'enroll.levels' , 'enroll.type'])->first();

        return response()->json(['message' => null, 'body' => $result ], 200);
    }

    public function manaraReport(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $GLOBALS['user_id'] = $request->user_id;
        $user = User::find($request->user_id);

        if($user->can('report_card/mfis/mfisg'))
            $allowed_levels=Permission::where('name','report_card/mfis/mfisg')->pluck('allowed_levels')->first();
        
        if($user->can('report_card/mfis/mfisb'))
            $allowed_levels=Permission::where('name','report_card/mfis/mfisb')->pluck('allowed_levels')->first();

        $allowed_levels=json_decode($allowed_levels);
        $student_levels = Enroll::where('user_id',$request->user_id)->pluck('level')->toArray();
        $check=(array_intersect($allowed_levels, $student_levels));

        if(count($check) == 0)
            return response()->json(['message' => 'You are not allowed to see report card', 'body' => null ], 200);

        $grade_category_callback = function ($qu) use ($request ) {
            $qu->whereNull('parent')
            ->with(['Children.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'GradeItems.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            }]); 
        };

        $course_callback = function ($qu) use ($request ) {
            $qu->Where(function ($query) {
                $query->where('name', 'LIKE' , "%Grades%")
                      ->orWhere('name','LIKE' , "%درجات%"); 
            });     
        };

        $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
            $qu->where('role_id', 3);
            $qu->whereHas('courses' , $course_callback)
                ->with(['courses' => $course_callback]); 
            $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                ->with(['courses.gradeCategory' => $grade_category_callback]); 
        };

        $result = User::whereId($request->user_id)->whereHas('enroll' , $callback)
                        ->with(['enroll' => $callback , 'enroll.levels' ,'enroll.year' , 'enroll.type' , 'enroll.classes'])->first();

        return response()->json(['message' => null, 'body' => $result ], 200);
    }


    public function manaraReportAll(Request $request)
    {
        $request->validate([
            'years'    => 'nullable|array',
            'years.*' => 'exists:academic_years,id',
            'types'    => 'nullable|array',
            'types.*' => 'exists:academic_types,id',
            'levels'    => 'nullable|array',
            'levels.*' => 'exists:levels,id',
            'classes'    => 'nullable|array',
            'classes.*' => 'exists:classes,id',
            'segments'    => 'nullable|array',
            'segments.*' => 'exists:segments,id',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id',
        ]);
        $result_collection = collect([]);
        $user_ids = $this->chain->getEnrollsByManyChain($request)->distinct('user_id')->pluck('user_id');
        foreach($user_ids as $user_id){
            $GLOBALS['user_id'] = $user_id;
            $grade_category_callback = function ($qu) use ($user_id , $request) {
                $qu->whereNull('parent')
                ->with(['Children.userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                },'GradeItems.userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                },'userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                }]); 
            };

            $course_callback = function ($qu) use ($request ) {
                $qu->Where(function ($query) {
                    $query->where('name', 'LIKE' , "%Grades%")
                        ->orWhere('name','LIKE' , "%درجات%"); 
                });     
            };

            $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
                $qu->where('role_id', 3);
                $qu->whereHas('courses' , $course_callback)
                    ->with(['courses' => $course_callback]); 
                $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                    ->with(['courses.gradeCategory' => $grade_category_callback]); 
            };
            $result = User::select('id','username','lastname', 'firstname')->whereId($user_id)->whereHas('enroll' , $callback)
                            ->with(['enroll' => $callback , 'enroll.levels' ,'enroll.year' , 'enroll.type' , 'enroll.classes'])->first();
            if($result != null)
                $result_collection->push($result);
        }
        return response()->json(['message' => null, 'body' => $result_collection ], 200);
    }

    public function haramaninReportAll(Request $request)
    {
        $request->validate([
            'years'    => 'nullable|array',
            'years.*' => 'exists:academic_years,id',
            'types'    => 'nullable|array',
            'types.*' => 'exists:academic_types,id',
            'levels'    => 'nullable|array',
            'levels.*' => 'exists:levels,id',
            'classes'    => 'nullable|array',
            'classes.*' => 'exists:classes,id',
            'segments'    => 'nullable|array',
            'segments.*' => 'exists:segments,id',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id',
        ]);
    
        $result_collection = collect([]);
        $user_ids = $this->chain->getEnrollsByManyChain($request)->distinct('user_id')->pluck('user_id');

        foreach($user_ids as $user_id){
            $GLOBALS['user_id'] = $user_id;

            $grade_category_callback = function ($qu) use ($request , $user_id ) {
                $qu->whereNull('parent')
                ->with(['Children.userGrades' => function($query) use ($request , $user_id){
                    $query->where("user_id", $user_id);
                },'GradeItems.userGrades' => function($query) use ($request , $user_id){
                    $query->where("user_id", $user_id);
                },'userGrades' => function($query) use ($request ,$user_id){
                    $query->where("user_id", $user_id);
                }]); 
            };


            $course_callback = function ($qu) use ($request ) {
                $qu->Where(function ($query) {
                    $query->where('name', 'LIKE' , "%Grades%")
                        ->orWhere('name','LIKE' , "%درجات%");
                });     
            };

            $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
                $qu->where('role_id', 3);
                $qu->whereHas('courses' , $course_callback)
                    ->with(['courses' => $course_callback]); 
                $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                    ->with(['courses.gradeCategory' => $grade_category_callback]); 
            };

            $result = User::select('id','username','lastname', 'firstname')->whereId($user_id)->whereHas('enroll' , $callback)
                            ->with(['enroll' => $callback , 'enroll.levels' , 'enroll.type'])->first();

            if($result != null)
                $result_collection->push($result);
        }
        return response()->json(['message' => null, 'body' => $result_collection ], 200);
    }


    public function forsanReportAll(Request $request)
    {
        $request->validate([
            'years'    => 'nullable|array',
            'years.*' => 'exists:academic_years,id',
            'types'    => 'nullable|array',
            'types.*' => 'exists:academic_types,id',
            'levels'    => 'nullable|array',
            'levels.*' => 'exists:levels,id',
            'classes'    => 'nullable|array',
            'classes.*' => 'exists:classes,id',
            'segments'    => 'nullable|array',
            'segments.*' => 'exists:segments,id',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id',
        ]);
    
        $result_collection = collect([]);
        $user_ids = $this->chain->getEnrollsByManyChain($request)->distinct('user_id')->pluck('user_id');

        foreach($user_ids as $user_id){
            $GLOBALS['user_id'] = $user_id;
            $grade_category_callback = function ($qu) use ($request, $user_id) {
                $qu->whereNull('parent')
                ->with(['Children.userGrades' => function($query) use ($request , $user_id){
                    $query->where("user_id", $user_id);
                },'GradeItems.userGrades' => function($query) use ($request, $user_id){
                    $query->where("user_id", $user_id);
                },'userGrades' => function($query) use ($request, $user_id){
                    $query->where("user_id", $user_id);
                }]); 
            };
    
            $course_callback = function ($qu) use ($request ) {
                $qu->Where(function ($query) {
                    $query->where('name', 'LIKE' , "%Grades%")
                          ->orWhere('name','LIKE' , "%درجات%"); 
                });     
            };
    
            $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
                $qu->where('role_id', 3);
                $qu->whereHas('courses' , $course_callback)
                    ->with(['courses' => $course_callback]); 
                $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                    ->with(['courses.gradeCategory' => $grade_category_callback]); 
            };
    
            $result = User::select('id','username','lastname', 'firstname')->whereId($user_id)->whereHas('enroll' , $callback)
                            ->with(['enroll' => $callback , 'enroll.levels' , 'enroll.type'])->first();
            if($result != null)
                $result_collection->push($result);
        }
        return response()->json(['message' => null, 'body' => $result_collection ], 200);
    }

    public function fglsReportAll(Request $request)
    {
        $request->validate([
            'years'    => 'nullable|array',
            'years.*' => 'exists:academic_years,id',
            'types'    => 'nullable|array',
            'types.*' => 'exists:academic_types,id',
            'levels'    => 'nullable|array',
            'levels.*' => 'exists:levels,id',
            'classes'    => 'nullable|array',
            'classes.*' => 'exists:classes,id',
            'segments'    => 'nullable|array',
            'segments.*' => 'exists:segments,id',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id',
        ]);
    
        $result_collection = collect([]);
        $user_ids = $this->chain->getEnrollsByManyChain($request)->where('role_id',3)->distinct('user_id')->pluck('user_id');

        $total_check=(array_intersect([6, 7 ,8 , 9, 10 , 11 , 12], $request->levels));
        foreach($user_ids as $user_id){
            $GLOBALS['user_id'] = $user_id;
            
            ////////////////////////////////
            $total = 0;
            $student_mark = 0;
            $grade_category_callback = function ($qu) use ($request, $user_id ) {
                $qu->where('name', 'First Term');
                $qu->with(['userGrades' => function($query) use ($request , $user_id){
                    $query->where("user_id", $user_id);
                }]);     
            };
    
            $callback = function ($qu) use ($request , $grade_category_callback) {
                $qu->where('role_id', 3);
                $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                    ->with(['courses.gradeCategory' => $grade_category_callback]); 
    
            };
    
            $result = User::whereId($user_id)->whereHas('enroll' , $callback)
                            ->with(['enroll' => $callback , 'enroll.levels' ,'enroll.year' , 'enroll.type' , 'enroll.classes'])->first();
            $result->enrolls =  collect($result->enroll)->sortBy('courses.created_at')->values();
    
            foreach($result->enrolls as $enroll){ 
                if($enroll->courses->gradeCategory != null)
                    $total += $enroll->courses->gradeCategory[0]->max;
    
                if($enroll->courses->gradeCategory[0]->userGrades != null)
                    $student_mark += $enroll->courses->gradeCategory[0]->userGrades[0]->grade;
                
                if(str_contains($enroll->courses->name, 'O.L'))
                    break;
    
            }
    
             $percentage = 0;
             if($total != 0)
                $percentage = ($student_mark /$total)*100;
    
            $evaluation = LetterDetails::select('evaluation')->where('lower_boundary', '<=', $percentage)
                        ->where('higher_boundary', '>', $percentage)->first();
    
            if($percentage == 100)
                $evaluation = LetterDetails::select('evaluation')->where('lower_boundary', '<=', $percentage)
                ->where('higher_boundary', '>=', $percentage)->first();
    
            $result->total = $total;
            $result->student_total_mark = $student_mark;
            $result->evaluation = $evaluation->evaluation;
            $result->add_total = true;
            unset($result->enroll);
            if(count($total_check) == 0)
                $result->add_total = false;
            ///////////////////////////////////////////////////
            if($result != null)
                $result_collection->push($result);
        }
        return response()->json(['message' => null, 'body' => $result_collection ], 200);
    }


    public function fglPrep3Report(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $allowed_levels=Permission::where('name','report_card/fgls')->pluck('allowed_levels')->first();
        $allowed_levels=json_decode($allowed_levels);

        $student_levels = Enroll::where('user_id',$request->user_id)->pluck('level')->toArray();
        $check=(array_intersect($allowed_levels, $student_levels));

        if(count($check) == 0)
            return response()->json(['message' => 'You are not allowed to see report card', 'body' => null ], 200);
        
        $GLOBALS['user_id'] = $request->user_id;
        $grade_category_callback = function ($qu) use ($request ) {
            $qu->whereNull('parent')
            ->with(['Children.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'GradeItems.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            }]); 
        };

        $course_callback = function ($qu) use ($request ) {
            $qu->Where(function ($query) {
                $query->where('name', 'LIKE' , "%Grades%")
                      ->orWhere('name','LIKE' , "%درجات%");
            });     
        };

        $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
            $qu->where('role_id', 3);
            $qu->whereHas('courses' , $course_callback)
                ->with(['courses' => $course_callback]); 
            $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                ->with(['courses.gradeCategory' => $grade_category_callback]); 
        };

        $result = User::whereId($request->user_id)->whereHas('enroll' , $callback)
                        ->with(['enroll' => $callback , 'enroll.levels' , 'enroll.type' , 'enroll.classes'])->first();

        return response()->json(['message' => null, 'body' => $result ], 200);
    }

    public function fglsPrep3ReportAll(Request $request)
    {
        $request->validate([
            'years'    => 'nullable|array',
            'years.*' => 'exists:academic_years,id',
            'types'    => 'nullable|array',
            'types.*' => 'exists:academic_types,id',
            'levels'    => 'nullable|array',
            'levels.*' => 'exists:levels,id',
            'classes'    => 'nullable|array',
            'classes.*' => 'exists:classes,id',
            'segments'    => 'nullable|array',
            'segments.*' => 'exists:segments,id',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id',
        ]);
    
        $result_collection = collect([]);
        $user_ids = $this->chain->getEnrollsByManyChain($request)->distinct('user_id')->pluck('user_id');

        foreach($user_ids as $user_id){
            $GLOBALS['user_id'] = $user_id;
            $grade_category_callback = function ($qu) use ($request, $user_id) {
                $qu->whereNull('parent')
                ->with(['Children.userGrades' => function($query) use ($request , $user_id){
                    $query->where("user_id", $user_id);
                },'GradeItems.userGrades' => function($query) use ($request, $user_id){
                    $query->where("user_id", $user_id);
                },'userGrades' => function($query) use ($request, $user_id){
                    $query->where("user_id", $user_id);
                }]); 
            };
    
            $course_callback = function ($qu) use ($request ) {
                $qu->Where(function ($query) {
                    $query->where('name', 'LIKE' , "%Grades%")
                          ->orWhere('name','LIKE' , "%درجات%"); 
                });     
            };
    
            $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
                $qu->where('role_id', 3);
                $qu->whereHas('courses' , $course_callback)
                    ->with(['courses' => $course_callback]); 
                $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                    ->with(['courses.gradeCategory' => $grade_category_callback]); 
            };
    
            $result = User::select('id','username','lastname', 'firstname')->whereId($user_id)->whereHas('enroll' , $callback)
                            ->with(['enroll' => $callback , 'enroll.levels' , 'enroll.type', 'enroll.classes'])->first();
            if($result != null)
                $result_collection->push($result);
        }
        return response()->json(['message' => null, 'body' => $result_collection ], 200);
    }

    public function manaraMonthlyReport(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month'   => 'required|in:Feb,March,April',
        ]);

        $GLOBALS['user_id'] = $request->user_id;
        $user = User::find($request->user_id);

        if($user->can('report_card/mfis/mfisg-monthly'))
            $allowed_levels=Permission::where('name','report_card/mfis/mfisg-monthly')->pluck('allowed_levels')->first();
        
        // if($user->can('report_card/mfis/mfisb'))
        //     $allowed_levels=Permission::where('name','report_card/mfis/mfisb')->pluck('allowed_levels')->first();

        $allowed_levels=json_decode($allowed_levels);
        $student_levels = Enroll::where('user_id',$request->user_id)->pluck('level')->toArray();
        $check=(array_intersect($allowed_levels, $student_levels));

        if(count($check) == 0)
            return response()->json(['message' => 'You are not allowed to see report card', 'body' => null ], 200);

        $grade_category_callback = function ($qu) use ($request ) {
            $qu->whereNull('parent')
            ->with(['Children.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'GradeItems.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            }]); 
        };

        $course_callback = function ($qu) use ($request ) { 
            $qu->where('name','LIKE', "%$request->month%");
        };

        $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
            $qu->where('role_id', 3);
            $qu->whereHas('courses' , $course_callback)
                ->with(['courses' => $course_callback]); 
            $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                ->with(['courses.gradeCategory' => $grade_category_callback]); 
        };

        $result = User::whereId($request->user_id)->whereHas('enroll' , $callback)
                        ->with(['enroll' => $callback , 'enroll.levels:id,name' ,'enroll.year:id,name' , 'enroll.type:id,name' , 'enroll.classes:id,name'])->first();

        return response()->json(['message' => null, 'body' => $result ], 200);
    }


    public function manaraMonthylReportAll(Request $request)
    {
        $request->validate([
            'month'   => 'required|in:Feb,March,April',
            'years'    => 'nullable|array',
            'years.*' => 'exists:academic_years,id',
            'types'    => 'nullable|array',
            'types.*' => 'exists:academic_types,id',
            'levels'    => 'nullable|array',
            'levels.*' => 'exists:levels,id',
            'classes'    => 'nullable|array',
            'classes.*' => 'exists:classes,id',
            'segments'    => 'nullable|array',
            'segments.*' => 'exists:segments,id',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id',
        ]);
        $result_collection = collect([]);
        $user_ids = $this->chain->getEnrollsByManyChain($request)->distinct('user_id')->pluck('user_id');
        foreach($user_ids as $user_id){
            $GLOBALS['user_id'] = $user_id;
            $grade_category_callback = function ($qu) use ($user_id , $request) {
                $qu->whereNull('parent')
                ->with(['Children.userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                },'GradeItems.userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                },'userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                }]); 
            };

            $course_callback = function ($qu) use ($request ) {
                $qu->where('name','LIKE', "%$request->month%");
            };

            $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
                $qu->where('role_id', 3);
                $qu->whereHas('courses' , $course_callback)
                    ->with(['courses' => $course_callback]); 
                $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                    ->with(['courses.gradeCategory' => $grade_category_callback]); 
            };
            $result = User::select('id','username','lastname', 'firstname')->whereId($user_id)->whereHas('enroll' , $callback)
                            ->with(['enroll' => $callback , 'enroll.levels:id,name' ,'enroll.year:id,name' , 'enroll.type:id,name' , 'enroll.classes:id,name'])->first();
            if($result != null)
                $result_collection->push($result);
        }
        return response()->json(['message' => null, 'body' => $result_collection ], 200);
    }



    public function fglFinalReport(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $allowed_levels=Permission::where('name','report_card/fgls')->pluck('allowed_levels')->first();
        $allowed_levels=json_decode($allowed_levels);
        $student_levels = Enroll::where('user_id',$request->user_id)->pluck('level')->toArray();
        $check=(array_intersect($allowed_levels, $student_levels));

        $total_check=(array_intersect([8 , 9, 10 , 11], $student_levels));

        if(count($check) == 0)
            return response()->json(['message' => 'You are not allowed to see report card', 'body' => null ], 200);


        $First_grade_category_callback = function ($qu) use ($request ) {
            $qu->where('name', 'First Term');
            $qu->with(['userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            }]);     
        };

        $Second_grade_category_callback = function ($qu) use ($request ) {
            $qu->where('name', 'Second Term')->orWhere('name','LIKE', "%actor%");
            $qu->with(['userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            }]);     
        };


        $course_callback = function ($qu) use ($request ) {
            $qu->orderBy('index', 'Asc');
        };

        $first_term = function ($qu) use ($request , $First_grade_category_callback , $course_callback) {
            $qu->whereHas('courses' , $course_callback)
            ->with(['courses' => $course_callback]); 
            $qu->where('role_id', 3);
            $qu->whereHas('courses.gradeCategory' , $First_grade_category_callback)
                ->with(['courses.gradeCategory' => $First_grade_category_callback]); 

        };


        $second_term = function ($qu) use ($request , $Second_grade_category_callback , $course_callback) {
            // $qu->orderBy('course', 'Asc');
            $qu->where('role_id', 3);
            $qu->whereHas('courses' , $course_callback)
                ->with(['courses' => $course_callback]); 
            $qu->whereHas('courses.gradeCategory' , $Second_grade_category_callback)
                ->with(['courses.gradeCategory' => $Second_grade_category_callback]); 

        };

        $first_term = User::select('id','firstname' , 'lastname')->whereId($request->user_id)->whereHas('enroll' , $first_term)
                        ->with(['enroll' => $first_term])->first();


        
        $second_term = User::select('id','firstname' , 'lastname')->whereId($request->user_id)->whereHas('enroll' , $second_term)
        ->with(['enroll' => $second_term , 'enroll.levels:id,name' ,'enroll.year:id,name' , 'enroll.type:id,name' , 'enroll.classes:id,name'])->first();

        $total = 0;
        $student_mark = 0;
        $result = collect([]);

        $olFound = true;
        foreach($first_term->enroll as $key => $enroll){   
            if(!$total_check)
                $second_term->enroll[$key]->courses->gradeCategory[0]->userGrades[0]->grade =
                ($enroll->courses->gradeCategory[0]->userGrades[0]->grade + $second_term->enroll[$key]->courses->gradeCategory[0]->userGrades[0]->grade)/2;

             if(isset($second_term->enroll[$key]->courses->gradeCategory[1])){
                $factor = $second_term->enroll[$key]->courses->gradeCategory[1]->max;

                $second_term->enroll[$key]->courses->gradeCategory[0]->userGrades[0]->grade =
                    ($enroll->courses->gradeCategory[0]->userGrades[0]->grade + $second_term->enroll[$key]->courses->gradeCategory[0]->userGrades[0]->grade) * $factor;

                    if($olFound == true){
                        if($enroll->courses->gradeCategory != null)
                            $total += ($enroll->courses->gradeCategory[0]->max + $second_term->enroll[$key]->courses->gradeCategory[0]->max) * $factor;
            
                        if($enroll->courses->gradeCategory[0]->userGrades != null)
                            $student_mark += $second_term->enroll[$key]->courses->gradeCategory[0]->userGrades[0]->grade;
                    }
                    unset($second_term->enroll[$key]->courses->gradeCategory[1]);
                    if(str_contains($enroll->courses->name, 'O.L'))
                        $olFound = false;
            }   
            
        }
        $second_term->add_total = false;
        if(count($total_check) > 0){
            $second_term->student_total_mark = $student_mark;
            $second_term->total = $total;
            $second_term->add_total = true;
        }
       
       return response()->json(['message' => null, 'body' => $second_term ], 200);

    }

    public function fglsFinalReportAll(Request $request)
    {
        $request->validate([
            'years'    => 'nullable|array',
            'years.*' => 'exists:academic_years,id',
            'types'    => 'nullable|array',
            'types.*' => 'exists:academic_types,id',
            'levels'    => 'nullable|array',
            'levels.*' => 'exists:levels,id',
            'classes'    => 'nullable|array',
            'classes.*' => 'exists:classes,id',
            'segments'    => 'nullable|array',
            'segments.*' => 'exists:segments,id',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id',
        ]);
    
        $result_collection = collect([]);
        $user_ids = $this->chain->getEnrollsByManyChain($request)->where('role_id',3)->distinct('user_id')->pluck('user_id');

        // $total_check=(array_intersect([6, 7 ,8 , 9, 10 , 11 , 12], $request->levels));
        foreach($user_ids as $user_id){
            $GLOBALS['user_id'] = $user_id;
            
            ////////////////////////////////
            $First_grade_category_callback = function ($qu) use ($request , $user_id ) {
                $qu->where('name', 'First Term');
                $qu->with(['userGrades' => function($query) use ($request , $user_id){
                    $query->where("user_id", $user_id);
                }]);     
            };
    
            $Second_grade_category_callback = function ($qu) use ($request,$user_id ) {
                $qu->where('name', 'Second Term');
                $qu->with(['userGrades' => function($query) use ($request , $user_id){
                    $query->where("user_id", $user_id);
                }]);     
            };
    
    
            $course_callback = function ($qu) use ($request ) {
                $qu->orderBy('index', 'Asc');
            };
    
            $first_term = function ($qu) use ($request , $First_grade_category_callback , $course_callback) {
                $qu->whereHas('courses' , $course_callback)
                ->with(['courses' => $course_callback]); 
                $qu->where('role_id', 3);
                $qu->whereHas('courses.gradeCategory' , $First_grade_category_callback)
                    ->with(['courses.gradeCategory' => $First_grade_category_callback]); 
    
            };
    
    
            $second_term = function ($qu) use ($request , $Second_grade_category_callback , $course_callback) {
                // $qu->orderBy('course', 'Asc');
                $qu->where('role_id', 3);
                $qu->whereHas('courses' , $course_callback)
                    ->with(['courses' => $course_callback]); 
                $qu->whereHas('courses.gradeCategory' , $Second_grade_category_callback)
                    ->with(['courses.gradeCategory' => $Second_grade_category_callback]); 
    
            };
    
            $first_term = User::whereId($user_id)->whereHas('enroll' , $first_term)
                            ->with(['enroll' => $first_term])->first();
    
            
            $second_term = User::whereId($user_id)->whereHas('enroll' , $second_term)
            ->with(['enroll' => $second_term , 'enroll.levels:id,name' ,'enroll.year:id,name' , 'enroll.type:id,name' , 'enroll.classes:id,name'])->first();
     
    
            foreach($first_term->enroll as $key => $enroll){   
                if(isset($second_term->enroll[$key]))
                    $second_term->enroll[$key]->courses->gradeCategory[0]->userGrades[0]->grade =
                    ($enroll->courses->gradeCategory[0]->userGrades[0]->grade + $second_term->enroll[$key]->courses->gradeCategory[0]->userGrades[0]->grade)/2;
            }
            ///////////////////////////////////////////////////
            if($second_term != null)
                $result_collection->push($second_term);
        }
        return response()->json(['message' => null, 'body' => $result_collection ], 200);
    }


    public function forsanMonthlyReport(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month'   => 'required|in:Feb,March,April',
        ]);

        $GLOBALS['user_id'] = $request->user_id;
        $user = User::find($request->user_id);

        if($request->month == 'Feb')
            $arabic_search = 'فبراير';
        if($request->month == 'March')
            $arabic_search = 'مارس';
        if($request->month == 'April')
            $arabic_search = 'بريل';

        if($user->can('report_card/forsan/monthly'))
            $allowed_levels=Permission::where('name','report_card/forsan/monthly')->pluck('allowed_levels')->first();
        

        $allowed_levels=json_decode($allowed_levels);
        $student_levels = Enroll::where('user_id',$request->user_id)->pluck('level')->toArray();
        $check=(array_intersect($allowed_levels, $student_levels));

        if(count($check) == 0)
            return response()->json(['message' => 'You are not allowed to see report card', 'body' => null ], 200);

        $grade_category_callback = function ($qu) use ($request ) {
            $qu->whereNull('parent')
            ->with(['Children.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'GradeItems.userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            },'userGrades' => function($query) use ($request){
                $query->where("user_id", $request->user_id);
            }]); 
        };

        $course_callback = function ($qu) use ($request, $arabic_search) { 
            $qu->where('name','LIKE', "%$request->month%")
                ->orWhere('name','LIKE', "%$arabic_search%");
        };

        $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
            $qu->where('role_id', 3);
            $qu->whereHas('courses' , $course_callback)
                ->with(['courses' => $course_callback]); 
            $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                ->with(['courses.gradeCategory' => $grade_category_callback]); 
        };

        $result = User::whereId($request->user_id)->whereHas('enroll' , $callback)
                        ->with(['enroll' => $callback , 'enroll.levels:id,name' ,'enroll.year:id,name' , 'enroll.type:id,name' , 'enroll.classes:id,name'])->first();

        return response()->json(['message' => null, 'body' => $result ], 200);
    }

    public function forsanMonthylReportAll(Request $request)
    {
        $request->validate([
            'month'   => 'required|in:Feb,March,April',
            'years'    => 'nullable|array',
            'years.*' => 'exists:academic_years,id',
            'types'    => 'nullable|array',
            'types.*' => 'exists:academic_types,id',
            'levels'    => 'nullable|array',
            'levels.*' => 'exists:levels,id',
            'classes'    => 'nullable|array',
            'classes.*' => 'exists:classes,id',
            'segments'    => 'nullable|array',
            'segments.*' => 'exists:segments,id',
            'courses' => 'array',
            'courses.*' => 'exists:courses,id',
        ]);
        $result_collection = collect([]);
        $user_ids = $this->chain->getEnrollsByManyChain($request)->distinct('user_id')->pluck('user_id');

        if($request->month == 'Feb')
            $arabic_search = 'فبراير';
        if($request->month == 'March')
            $arabic_search = 'مارس';
        if($request->month == 'April')
            $arabic_search = 'بريل';

        foreach($user_ids as $user_id){
            $GLOBALS['user_id'] = $user_id;
            $grade_category_callback = function ($qu) use ($user_id , $request) {
                $qu->whereNull('parent')
                ->with(['Children.userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                },'GradeItems.userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                },'userGrades' => function($query) use ($user_id , $request){
                    $query->where("user_id", $user_id);
                }]); 
            };

            $course_callback = function ($qu) use ($request, $arabic_search ) {
                $qu->where('name','LIKE', "%$request->month%")
                    ->orWhere('name','LIKE', "%$arabic_search%");
            };

            $callback = function ($qu) use ($request , $course_callback , $grade_category_callback) {
                $qu->where('role_id', 3);
                $qu->whereHas('courses' , $course_callback)
                    ->with(['courses' => $course_callback]); 
                $qu->whereHas('courses.gradeCategory' , $grade_category_callback)
                    ->with(['courses.gradeCategory' => $grade_category_callback]); 
            };
            $result = User::select('id','username','lastname', 'firstname')->whereId($user_id)->whereHas('enroll' , $callback)
                            ->with(['enroll' => $callback , 'enroll.levels:id,name' ,'enroll.year:id,name' , 'enroll.type:id,name' , 'enroll.classes:id,name'])->first();
            if($result != null)
                $result_collection->push($result);
        }
        return response()->json(['message' => null, 'body' => $result_collection ], 200);
    }

}
