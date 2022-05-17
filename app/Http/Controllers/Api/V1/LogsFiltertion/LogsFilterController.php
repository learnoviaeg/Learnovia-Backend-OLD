<?php

namespace App\Http\Controllers\Api\V1\LogsFiltertion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Log;
use App\User;
use Auth;
use App\AuditLog;
use App\Http\Controllers\HelperController;
use App\Paginate;
use App\Exports\AuditlogExport;
use Excel;
use Illuminate\Support\Facades\Storage;
use App\AcademicYear;
use DB;
use App\Http\Resources\Api\LogsFiltertion\LogsFilterResource;

class LogsFilterController extends Controller
{
    public function logs_filteration(Request $request)
    {
        $yesterday =  date("Y-m-d h:i:s", strtotime( '-1 days' ));
        $right_now =  date("Y-m-d H:i:s");
        $first_created = AuditLog::first()->created_at;
        $first_created_at = $first_created != null ? $first_created : User::first()->created_at;

    	$user_id      = isset($request->user_id) ? $request->user_id : null;
    	$action       = isset($request->action) ? $request->action : null;
        $model        = isset($request->model) ? $request->model : null;
        $role_id      = isset($request->role_id) ? $request->role_id : null;
        // chain attributes
        $year_id    = isset($request->year_id) ? $request->year_id : null;
        $type_id    = isset($request->type_id) ? $request->type_id : null;
        $level_id   = isset($request->level_id) ? $request->level_id : null;
        $class_id   = isset($request->class_id) ? $request->class_id : null;
        $segment_id = isset($request->segment_id) ? $request->segment_id : null;
        $course_id  = isset($request->course_id) ? $request->course_id : null;
        $pagination = isset($request->paginate) ? $request->paginate : 15;
        $limit      = isset($request->paginate) ? $request->paginate : 15;
        $skip       = ($request->page -1) * $limit;
        // chain attributes

        $defaultFilters = array(
	    	'subject_type' => $model,
	    	'action'       => $action,
	    	'role_id'      => $role_id,
	    	'user_id'      => $user_id,
	    );

	    $chainFilters = array(
	    	'year_id'       => $year_id,
	    	'type_id'       => $type_id,
	    	'level_id'      => $level_id,
	    	'class_id'      => $class_id,
	    	'segment_id'    => $segment_id,
	    	'course_id'     => $course_id,
	    );

	    $defaultFilters = array_filter($defaultFilters);
	    $chainFilters   = array_filter($chainFilters);

	    $currentYear = AcademicYear::Get_current()->id;

        $notNeeeded = ['userQuizAnswer', 'userQuiz', 'Material', 'CourseItem', 'UserCourseItem', 'FileLesson', 'pageLesson', 'MediaLesson', 'QuizLesson', 'AssignmentLesson', 'AnnouncementsChain', 'quiz_questions'];

        // time start
		        $start_date = isset($request->start_date) ? $request->start_date  : $first_created_at;
		        $end_date   = isset($request->end_date) ? $request->end_date  : date("Y-m-d H:i:s");
        // time end

		 $first_hit      = 0;
		 $default_filter = 0;

		 // start default
		if(count($defaultFilters) <= 0){
        	$default_filter = 0;
        }else{
        	$default_filter = 1;
        }
		 // end default 

        // start chain
		if(count($chainFilters) <= 0){
        	$chain_filter = 0;
        }else{
        	$chain_filter = 1;
        }
		 // end chain 

        // no time detected , no filter selected case
        if ( $default_filter == 0 && $chain_filter == 0 ) {
        	  $first_hit   = 1;
        	  // get last 24 
		    	if ($request->start_date  == null && $request->end_date == null) {
		    		$whereStart  =  date("Y-m-d h:i:s", strtotime( '-1 days' ));
		            $whereEnd    =  date("Y-m-d H:i:s");
		    	}else{
		    		$whereStart = $start_date;
			        $whereEnd   = $end_date;
		    	}
		    	$collection = $this->checkTimeFilter($currentYear, $pagination, $notNeeeded, $whereStart, $whereEnd);
		        LogsFilterResource::collection($collection);
		    	return response()->json(['data' => $collection, 'status_code' => 200], 200);
        }
      
	    ///// start case default filter 1
	    if ($default_filter == 1) {
		    $data = AuditLog::whereNotIn('subject_type', $notNeeeded)
		                            ->where('created_at', '>=', $start_date)
		                            ->where('created_at', '<=', $end_date)
		            ->Where(function($query) use ($defaultFilters)
					{
					    foreach($defaultFilters as $key => $value) 
					    {
					    	$query->where($key, $value);
					    }
					});

				if ($chain_filter == 1) 
			    {
				    $data = $data->Where(function($query2) use ($chainFilters)
							{
							    foreach($chainFilters as $key2 => $value2) 
							    {
							    	$query2->where($key2, 'like', "%{$value2}%");
							    }
							});
				}
		} ////// end case default filter 1
		else{
			if ($chain_filter == 1) 
			{
				    $data = AuditLog::whereNotIn('subject_type', $notNeeeded)
				                    ->where('created_at', '>=', $start_date)
		                            ->where('created_at', '<=', $end_date)
		                    ->Where(function($query2) use ($chainFilters)
							{
							    foreach($chainFilters as $key2 => $value2) 
							    {
							    	$query2->where($key2, 'like', "%{$value2}%");
							    }
							});
			}
		}  // end else

	    //$collection = $data->simplePaginate($pagination);
	    $collection = $data->select('id', 'action','subject_type', 'subject_id', 'user_id', 'created_at', 'host', 'hole_description', 'item_name', 'item_id')->orderBy('id', 'DESC')->paginate($pagination);

	    LogsFilterResource::collection($collection);
	    return response()->json(['data' => $collection, 'status_code' => 200], 200);
    }

		public function checkTimeFilter($currentYear, $pagination, $notNeeeded, $whereStart, $whereEnd)
		{
			// return $whereEnd;
			$chain_ids = AuditLog::whereNotIn('subject_type', $notNeeeded)
			                        ->where('created_at', '>=', $whereStart)
		                            ->where('created_at', '<=', $whereEnd)
		                            ->where('year_id', 'like', "%{$currentYear}%")
		                            ->select('id', 'action','subject_type', 'subject_id', 'user_id', 'created_at', 'host', 'hole_description', 'item_name', 'item_id')
		                            ->orderBy('id', 'DESC')
		                            ->paginate($pagination);

		    return $chain_ids;
		}
    // export section
        /*if ($request->has('export') && $request->export == 1) {
            //return Excel::download(new AuditlogExport($data), 'auditlogs.xlsx');
            $filename = uniqid();
            $file     = Excel::store(new AuditlogExport($data), 'AuditLog'.$filename.'.xlsx','public');
            $file     = url(Storage::url('AuditLog'.$filename.'.xlsx'));
            return HelperController::api_response_format(201,$file, __('messages.success.link_to_file')); 
    
        }*/

}
