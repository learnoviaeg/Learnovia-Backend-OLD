<?php

namespace Modules\Attendance\Http\Controllers;

use App\Component;
use App\GradeCategory;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\GradeCategoryController;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Attendance\Entities\Attendance;
use Modules\Attendance\Entities\AttendanceLog;
use Modules\Attendance\Entities\AttendanceSession;

use App\Enroll;
use App\Level;
use App\CourseSegment;
use Illuminate\Support\Facades\DB;
use Modules\Attendance\Entities\AttendanceStatus;
use Modules\Attendance\Jobs\AttendanceGradeItems;
use Modules\Attendance\Jobs\AttendanceSessionsJob;
use stdClass;

class AttendanceController extends Controller
{
    public function install()
    {
        if (\Spatie\Permission\Models\Permission::whereName('attendance/add')->first() != null) {
            return \App\Http\Controllers\HelperController::api_response_format(400, null, 'This Component is installed before');
        }

        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/add', 'title' => 'add attendance']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/add-log', 'title' => 'add attendance log']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-users-in-attendance', 'title' => 'get all users in attendance']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-attendance', 'title' => 'get attendance','dashboard' => 1]);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/delete-attendance', 'title' => 'delete attendance']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/edit-attendance', 'title' => 'edit attendance']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-users-in-session', 'title' => 'get all users in session']);
        // \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-all-taken-users-in-session', 'title' => 'get all taken users in session']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/add-session', 'title' => 'add session']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'site/attendance/bulk/attendance', 'title' => 'add session']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/status/add', 'title' => 'add attendance status']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/status/update', 'title' => 'update attendance status']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/status/delete', 'title' => 'delete attendance status']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/attend-report', 'title' => 'report of attendance']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/status/get', 'title' => 'Status', 'dashboard' => 1]);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-session', 'title' => 'get session and status', 'dashboard' => 1]);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/update-session', 'title' => 'update session']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/delete-session', 'title' => 'delete session']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-session-by-id', 'title' => 'get session by id']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-all-sessions', 'title' => 'get all sessions']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-users-taken-in-session', 'title' => 'get all taken users in session']);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/get-sessions', 'title' => 'Sessions', 'dashboard' => 1]);
        \Spatie\Permission\Models\Permission::create(['guard_name' => 'api', 'name' => 'attendance/view-students-in-session', 'title' => 'view students in session']);

        $role = \Spatie\Permission\Models\Role::find(1);
        $role->givePermissionTo('attendance/add');
        $role->givePermissionTo('attendance/add-log');
        $role->givePermissionTo('attendance/get-users-in-attendance');
        $role->givePermissionTo('attendance/get-users-in-session');
        $role->givePermissionTo('attendance/get-all-taken-users-in-session');
        $role->givePermissionTo('attendance/add-session');
        $role->givePermissionTo('site/attendance/bulk/attendance');
        $role->givePermissionTo('attendance/status/add');
        $role->givePermissionTo('attendance/status/update');
        $role->givePermissionTo('attendance/status/delete');
        $role->givePermissionTo('attendance/status/get');
        $role->givePermissionTo('attendance/get-attendance');
        $role->givePermissionTo('attendance/delete-attendance');
        $role->givePermissionTo('attendance/edit-attendance');
        $role->givePermissionTo('attendance/get-users-taken-in-session');
        $role->givePermissionTo('attendance/get-sessions');
        $role->givePermissionTo('attendance/get-session');
        $role->givePermissionTo('attendance/attend-report');
        $role->givePermissionTo('attendance/update-session');
        $role->givePermissionTo('attendance/delete-session');
        $role->givePermissionTo('attendance/get-all-sessions');
        $role->givePermissionTo('attendance/view-students-in-session');

        Component::create([
            'name' => 'Attendance',
            'module' => 'Attendance',
            'model' => 'Attendance',
            'type' => 2,
            'active' => 1
        ]);

        return \App\Http\Controllers\HelperController::api_response_format(200, null, 'Component Installed Successfully');
    }

    public function get_all_users_in_attendence(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:attendances,id',
        ]);
        $attendane = Attendance::find($request->id);
        $Course_Segments = Attendance::get_CourseSegments_by_AttendenceID($request->id);
        $users =   User::whereIn('level', $attendane->allowed_levels)->get();
        if(($Course_Segments)== null){
            $users = Enroll::whereIn('course_segment', $Course_Segments)->with('user')->get();
        }
        foreach($users as $user)
        {
            if(isset($user->attachment))
                $user->picture=$user->attachment->path;
            // unset($user->attachment);
        }
        return HelperController::api_response_format(200, $users, 'Users are.....');
    }

    public function get_all_users_in_session(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
            'year' => 'exists:academic_years,id',
            'type' => 'exists:academic_types,id',
            'level' => 'exists:levels,id',
            'class' => 'exists:classes,id',
        ]);
        $session = AttendanceSession::where('id', $request->session_id)->first();
        $AlreadyTakenUsers = AttendanceLog::where('session_id', $request->session_id)->pluck('student_id');
        $course_segments = [];
        $course_segments[] = $session->course_segment_id;
        if (is_null($course_segments[0])) {
            $course_segments = GradeCategoryController::getCourseSegment($request);
        }
        $users = Enroll::whereIn('course_segment', $course_segments)->with(['users' => function ($query) use ($AlreadyTakenUsers) {
            $query->whereNotIn('id', $AlreadyTakenUsers);
        }])->get()->pluck('users');
        foreach($users as $user)
        {
            if(isset($user->attachment))
                $user->picture=$user->attachment->path;
            // unset($user->attachment);
        }
        return HelperController::api_response_format(200, $users, 'Users are.....');
    }

    public function get_all_taken_users_in_session(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
        ]);
        $AlreadyTakenUsers = AttendanceLog::where('session_id', $request->session_id)->pluck('student_id');
        $users = User::whereIn('id', $AlreadyTakenUsers)->get();
        return HelperController::api_response_format(200, $users, 'Users are....');
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return message to tell that all session with or without course segments  are created
     */
    public static function createAttendance(Request $request)
    {
        if ($request->attendance_type == Attendance::$FIRST_TYPE) {
            foreach ($request->levels as $level) {
                $levels[] = $level['id'];
                $classes[] = $level['classes'];
                $courses[] = $level['courses'];
            }
        } elseif ($request->attendance_type == Attendance::$SECOND_TYPE ) {
            foreach ($request->levels as $level) {
                foreach ($level['periods'] as $periods) {
                    $courses[] = $periods['courses'];
                }
                $levels[] = $level['id'];
                $classes[] = $level['classes'];
            }
        }
        $attendance = Attendance::create(['name' => $request->name,
            'type' => $request->attendance_type,
            'graded' => $request->graded,
            'allowed_levels' => isset($levels)?serialize($levels):null,
            'allowed_courses' => isset($courses)?serialize($courses):null ,
            'allowed_classes' => isset($classes)?serialize($classes):null ,
            'year_id' => $request->year,
            'segment_id' => $request->segment,
            'type_id' => $request->type,
            'start_date' => $request->start,
            'end_date' => $request->end

        ]);
        $default = AttendanceStatus::defaultStatus();
        foreach ($default as $status) {
            $status['attendance_id'] = $attendance->id;
            AttendanceStatus::create($status);
        }
        return $attendance;
    }

    public function create(Request $request)
    {
        $request->validate([
            'attendance_type' => 'required|integer|min:1|max:2',
        ]);
        if ($request->attendance_type == Attendance::$FIRST_TYPE)
            $request->validate(Attendance::FirstTypeRules());
        else if ($request->attendance_type == Attendance::$SECOND_TYPE) {
            $request->validate([
                'sessions.times' => 'required|integer',
            ]);
            $request->validate(Attendance::SecondTypeRules($request->sessions['times']));
        }
        if ($request->attendance_type == Attendance::$SECOND_TYPE && !Auth::User()->can('site/attendance/bulk/attendance')) {
            return HelperController::api_response_format(200, 'does not have the right permissions ');
        }
        $user_id = Auth::User()->id;
        $attendance = self::createAttendance($request);
        if ($request->attendance_type == Attendance::$FIRST_TYPE && $request->graded == 1) {
            $jop = (new  AttendanceGradeItems($request->all(), Attendance::$FIRST_TYPE, null));
            dispatch($jop);
            return HelperController::api_response_format(200, $attendance, 'attendance created successfully with grade Items');
        } elseif ($request->attendance_type == Attendance::$SECOND_TYPE &&  $request->graded == 1) {
            foreach ($request['levels'] as $level) {
                $request['type'] = [$request['type']];
                $request['classes'] = $level['classes'];
                foreach ($level['periods'] as $period) {
                    $req = new Request([
                        'year' => $request['year'],
                        'segments' => [$request['segment']],
                        'type' => $request['type'],
                        'levels' => [$level['id']],
                        'classes' => $level['classes'],
                        'courses' => [$period['courses']]
                    ]);
                    $course_segments = GradeCategoryController::getCourseSegmentWithArray($req);
                    $gradeCategories = GradeCategory::where('name', $period['grade_category_name'])->whereIn('course_segment_id', $course_segments)->get();
                    $job = new AttendanceGradeItems($request->all(), Attendance::$SECOND_TYPE, $gradeCategories);
                    dispatch($job);
                    $job = new AttendanceSessionsJob($request->all(), $attendance->id, $course_segments, $period, $user_id);
                    dispatch($job);
                }
            }
        }elseif ($request->attendance_type == Attendance::$SECOND_TYPE  && $request->graded == 0){
            $request->validate([
                'allowed_levels' => 'array',
                'allowed_levels.*' => 'exists:levels,id',
            ]);

            if(isset($request->allowed_levels)){
                $attendance->update([
                    'allowed_levels' => serialize($request->allowed_levels)
                ]);
            }
            $alldays = Attendance::getAllWorkingDays($request->start, $request->end);
                foreach ($alldays as $day) {
                    for ($i = 0; $i < $request->sessions['times']; $i++) {
                        AttendanceSession::create([
                            'attendance_id' =>  $attendance->id,
                            'taker_id' => $user_id,
                            'date' => $day,
                            'course_segment_id' => null,
                            'from' => $request->sessions['time'][$i]['start'],
                            'to' => $request->sessions['time'][$i]['end']
                        ]);
                    }
                }
            }
        return HelperController::api_response_format(200, $attendance, 'attendance created successfully');
    }

    public function createSession(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
        ]);
        $attendance = Attendance::find($request->attendance_id);
        $user_id = Auth::User()->id;
        switch ($attendance->type) {
            case  1 :
                $array = [
                    'days' => 'array|required|min:1',
                    'days.*.name' => 'required|string',
                    'days.*.from' => 'required|regex:/(\d+\:\d+)/',
                    'days.*.to' => 'required|regex:/(\d+\:\d+)/',
                    'days.*.date' => 'required|date',
                    'repeat_untill' => 'date',
                    'levels' => 'required|array|min:1',
                    'levels.*.id' => 'exists:levels,id',
                    'levels.*.class' => 'required|exists:classes,id',
                    'levels.*.course' => 'required|exists:courses,id',
                ];
                $request->validate($array);
                foreach ($request->levels as $level) {
                    $req = new Request([
                        'year' => $attendance->year_id,
                        'segments' => [$attendance->segment_id],
                        'type' => [$attendance->type_id],
                        'levels' => [$level['id']],
                        'classes' => [$level['class']],
                        'courses' => [$level['course']]
                    ]);
                    $course_segments = GradeCategoryController::getCourseSegmentWithArray($req);
                    if (!((Attendance::check_in_array($attendance->allowed_classes, $req->classes)) &&
                        (Attendance::check_in_array($attendance->allowed_levels, $req->levels)) &&
                        (Attendance::check_in_array($attendance->allowed_courses, $req->courses)))
                    ) {
                        return HelperController::api_response_format(400, 'Something wrong with untill date');
                    }

                    if (!isset($request->repeat_untill)) {
                        foreach ($course_segments as $course_segment) {
                            foreach ($request->days as $day) {
                                AttendanceSession::create([
                                    'attendance_id' => $attendance->id,
                                    'taker_id' => $user_id,
                                    'date' => $day['date'],
                                    'from' => $day['from'],
                                    'to' => $day['to'],
                                    'course_segment_id' => $course_segment
                                ]);
                            }
                        }
                        return HelperController::api_response_format(200, 'Sessions are created successfully');
                    }
                    if ($request->repeat_untill > $attendance->end_date) {
                        return HelperController::api_response_format(400, 'Something wrong with data');
                    }

                    foreach ($course_segments as $course_segment) {
                        foreach ($request->days as $day) {
                            $startDate = Carbon::parse(Carbon::parse($day['date']))->next(Attendance::GetCarbonDay($day['name']));
                            $endDate = Carbon::parse($request->repeat_untill);

                            for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                                $alldays[] = $date->format('Y-m-d');
                                AttendanceSession::create([
                                    'attendance_id' => $attendance->id,
                                    'taker_id' => $user_id,
                                    'date' => $date->format('Y-m-d'),
                                    'from' => $day['from'],
                                    'to' => $day['to'],
                                    'course_segment_id' => $course_segment
                                ]);
                            }
                        }
                    }
                }
                break;
            case 2:
                $request->validate([
                    'sessions.times' => 'required|integer',
                ]);

                $array = [
                    'levels' => 'required|array|min:1',
                    'levels.*.id' => 'exists:levels,id',
                    'levels.*.classes' => 'required|array',
                    'levels.*.classes' => 'required|exists:classes,id',
                    'levels.*.periods' => 'required|array',
                    'levels.*.periods.*.courses' => 'required|exists:courses,id',
                    'levels.*.periods.*.from' => 'required|date',
                    'levels.*.periods.*.to' => 'required|date',
                    'levels.*.periods.*.grade_category_name' => 'required|string|exists:grade_categories,name',
                    'sessions' => 'required',
                    'sessions.time.*.start' => 'required|regex:/(\d+\:\d+)/',
                    'sessions.time.*.end' => 'required|regex:/(\d+\:\d+)/',
                ];
                $array['sessions.time'] = 'required|array|size:' . $request->sessions['times'];
                $request->validate($array);
                $request['start'] = $attendance->start_date;
                $request['end'] = $attendance->end_date;
                foreach ($request->levels as $level) {
                    foreach ($level['periods'] as $period) {
                        $req = new Request([
                            'year' => $attendance->year_id,
                            'segments' => [$attendance->segment_id],
                            'type' => [$attendance->type_id],
                            'levels' => [$level['id']],
                            'classes' => $level['classes'],
                            'courses' => [$period['courses']]
                        ]);
                        if (!((Attendance::check_in_array($attendance->allowed_classes, $req->classes)) &&
                            (Attendance::check_in_array($attendance->allowed_levels, $req->levels)) &&
                            (Attendance::check_in_array($attendance->allowed_courses, $req->courses)))
                        ) {
                            return HelperController::api_response_format(400, 'Something wrong with data');
                        }
                        $course_segments = GradeCategoryController::getCourseSegmentWithArray($req);
                        $job = new AttendanceSessionsJob($request->all(), $attendance->id, $course_segments, $period, $user_id);
                        dispatch($job);
                    }
                }
                return HelperController::api_response_format(200, 'Sessions are created successfully');
        }
        return HelperController::api_response_format(200, 'Sessions are created successfully');
    }

    public function viewstudentsinsessions(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
        ]);
        $session = AttendanceSession::where('id', $request->session_id)->first();
        $flag = true;
        if($session->course_segment_id == null){
            $flag = false;
            $request = new Request([
                'year' => $session->Attendence->year_id,
                'segments' => [$session->Attendence->segment_id],
                'type' => [$session->Attendence->type_id],
                'levels' => $session->Attendence->allowed_levels,
                'classes' => $session->Attendence->allowed_classes,
                'courses' => $session->Attendence->allowed_courses
            ]);
            $course_segment = GradeCategoryController::getCourseSegmentWithArray($request);
        }
        if($flag)
            $course_segment = [$session->course_segment_id];
        $users_ids = Enroll::whereIn('course_segment', $course_segment)->get()->pluck('user_id');
        $logs = AttendanceLog::where('session_id', $request->session_id)->whereIn('student_id', $users_ids)->get();
        $users = User::whereIn('id', $users_ids)->get();
        foreach ($users as $user) {
            $user['flag'] = false;
            $user['status_id'] = null;
            $temp = collect();
            foreach ($logs as $log) {
                if ($log->student_id == $user->id) {
                    $user['flag'] = true;
                    $user['status_id'] = $log->status_id;
                    $temp->push($log);
                }
            }
            $user['log'] = $temp->toArray();
        }
        foreach($users as $user)
        {
            if(isset($user->attachment))
                $user->picture=$user->attachment->path;
            //unset($user->attachment);
        }
        return HelperController::api_response_format(200, $users, 'Users are.....');
    }

    public function GetAllSessionDay(Request $request)
    {
        $data=array();
        $i=0;
        $courses=Enroll::where('user_id',Auth::id())->pluck('course_segment');
        $Sessions=AttendanceSession::whereIn('course_segment_id',$courses)->where('date',Carbon::today())->get();
        if($request->filled('id'))
            $Sessions=AttendanceSession::where('id',$request->id)->get();
        foreach($Sessions as $session)
        {
            $data[$i]['course']=$session->Course_Segment->courses[0]->name;
            $data[$i]['attendance']=$session->Attendence;
            $data[$i]['from']= $session->from;
            $data[$i]['to']= $session->to;
            $data[$i]['status']= '-';
            if(count($session->logs)>0)
                $data[$i]['status']= $session->logs[0]->status->letter;
            if($request->user()->can('site/course/teacher'))
                $data[$i]['status']= '-';
            $i++;
        }
        return HelperController::api_response_format(200, $data, 'there is your session & status');
    }

    public function Attendance_Report(Request $request)
    {
        //return Hosts::select(['URL' , DB::raw("COUNT(*) as hits")])->groupBy('URL')->get();
        $request->validate([
            'session_id' => 'exists:attendance_sessions,id',
        ]);
        $logs=[];
        $sessions=[];
        $i=0;

        if ($request->user()->can('site/course/student')) {
            $sessions = AttendanceLog::where('student_id', Auth::user()->id)->distinct('session_id')->pluck('session_id');
            $logs = AttendanceLog::select(['*' , DB::raw("COUNT(*) as count")])
            ->where('student_id', Auth::user()->id)
            ->groupBy('status_id');
        }
        else if($request->user()->can('site/course/teacher')) {
            $sessions = AttendanceLog::where('taker_id', Auth::user()->id)->distinct('session_id')->pluck('session_id');
            $logs = AttendanceLog::select(['*' , DB::raw("COUNT(*) as count")])
            ->where('taker_id', Auth::user()->id)
            ->groupBy('status_id');
        }
        else if($request->user()->can('site/restrict'))
        {
            $logs = AttendanceLog::select(['*' , DB::raw("COUNT(*) as count")])->groupBy('status_id');
            $sessions = AttendanceLog::distinct('session_id')->pluck('session_id');
        }
        
        if($request->filled('session_id'))
            $logs = $logs->where('session_id' , $request->session_id);
        $logs = $logs->get();
        $total = 0;
        if(count($logs) > 0)
            $total = array_sum($logs->pluck('count')->toArray());
        $result = [];
        foreach($logs as $log){
            $temp = new stdClass();
            $temp->count = $log->count;
            $temp->label = $log->status->letter;
            $temp->description = $log->status->descrption;
            $temp->percentage = (double)(($log->count * 100) / $total);
            $result['data'][$i] = $temp;
            $i++;
        }
        $result['sessions']=$sessions;
        return HelperController::api_response_format(200 , $result , '');
    }

    public function getAttendance(Request $request)
    {
        $request->validate([
            'id' => 'exists:attendances',
            'search' => 'nullable',
        ]);
        if($request->filled('id'))
        {
            $attendance=Attendance::where('id',$request->id)->get();
            return HelperController::api_response_format(200, $attendance);
        }
        $attendance=Attendance::where('name', 'LIKE' , "%$request->search%")->get(['name','allowed_levels','type' , 'id']);
        foreach($attendance as $attend)
        {
            $temp = [];
            foreach($attend->allowed_levels as $level)
            {
                $level  = Level::find($level);
                $temp[] = $level->name;
            }
            $attend->levels = $temp;
            if($attend->type == 1)
                $attend->type = 'per session';
            if($attend->type == 2)
                $attend->type = 'Daily';
        }
        return HelperController::api_response_format(200, $attendance, 'there is all your logs');
    }

    public function update_session(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
            'attendance_id' => 'exists:attendances,id',
            'taker_id' => 'exists:users,id',
            'date' =>  'date',
            'from' =>  'regex:/(\d+\:\d+)/',
            'to' => 'regex:/(\d+\:\d+)/',
            'course_segment_id' => 'exists:course_segments,id',
        ]);
        $session = AttendanceSession::find($request->session_id);
        $attendance = Attendance::find($session->attendance_id);
        if($request->filled('attendance_id'))
            $attendance = Attendance::find($request->attendance_id);
        $req = new Request([
            'classes' =>$attendance->allowed_classes,
            'levels' => $attendance->allowed_levels,
            'courses' => $attendance->allowed_courses
        ]);
        $course_segments= GradeCategoryController::getCourseSegmentWithArray($req);
        if($request->filled('course_segment_id') && in_array($course_segments , $request->course_segment_id))
            $session->course_segment_id = $request->course_segment_id;

        if($request->filled('attendance_id'))
            $session->attendance_id = $request->attendance_id;
        if($request->filled('taker_id'))
            $session->taker_id = $request->taker_id;
        if($request->filled('date'))
            $session->date = $request->date;
        if($request->filled('from'))
            $session->from = $request->from;
        if($request->filled('to'))
            $session->to = $request->to;
        $session -> save();
        return HelperController::api_response_format(400, $session ,'Session is updated successfully');

    }

    public function delete_session(Request $request){
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
        ]);
        $session = AttendanceSession::find($request->session_id);
        $session ->delete();
        return HelperController::api_response_format(200, null ,'Session is deleted successfully');
    }

    public function get_session_byID(Request $request){
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
        ]);
        $session = AttendanceSession::find($request->session_id);
        return HelperController::api_response_format(200, $session);
    }

    public function deleteAttendance(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:attendances',
        ]);

        $attendance=Attendance::find($request->id);
        $attendance->delete();
        return HelperController::api_response_format(200, $attendance, 'Attendance deleted Successfully');
    }

    public function editAttendance(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:attendances',
            'year' => 'exists:academic_years,id',
            'type' => 'exists:academic_types,id',
            'segment' => 'exists:segments,id',
            'name' => 'string',
            'type' => 'integer',
            'graded' => 'int',
            'start_date' => 'date',
            'end_date' => 'date',
        ]);

        $attendance=Attendance::find($request->id);

        $req = new Request([
            'year' => (isset($request->year)) ? $request->year : null,
            'segment' => (isset($request->segment)) ? $request->segment : null,
            'type' => (isset($request->type)) ? $request->type : null
        ]);
        $courseseg=GradeCategoryController::getCourseSegment($req);
        $courses=CourseSegment::whereIn('id',$courseseg)->pluck('course_id');
        $coursss= array_values(array_unique($courses->toArray()));
        $classes = CourseSegment::whereIn('id',$coursss)->with('segmentClasses.classLevel.yearLevels')->get();
        // return $classes;
        $levels=[];
        $classss=[];
        foreach($classes as $class)
        {
            $classss[] = $class->segmentClasses[0]->classLevel[0]->class_id;
            $levels[] = $class->segmentClasses[0]->classLevel[0]->yearLevels[0]->level_id;
        }
        if(!$courses)
            return HelperController::api_response_format(200, 'there is no course segment');

        $attendance->update([
            'name' => ($request->name == null) ? $attendance->name : $request->name,
            'type' => (isset($request->type)) ? $request->type :$attendance->type,
            'allowed_levels' => serialize(array_values(array_unique($levels))),
            'allowed_classes' => serialize((array_values(array_unique($classss)))) ,
            'allowed_courses' => serialize($coursss),
            'graded' => (isset($request->graded)) ? $request->graded :$attendance->graded,
            'year_id' => (isset($request->year)) ? $request->year :$attendance->year_id,
            'segment_id' => (isset($request->segment)) ? $request->segment :$attendance->segment_id,
            'type_id' => (isset($request->type)) ? $request->type :$attendance->type_id,
            'start_date' => (isset($request->start_date)) ? $request->start_date :$attendance->start_date,
            'end_date' => (isset($request->end_date)) ? $request->end_date :$attendance->end_date,
        ]);
        return HelperController::api_response_format(200, $attendance);
    }

    public function getAllSessions(Request $request)
    {
        $final=[];
        $test=[];

        $Sessions = AttendanceSession::all();
        if(isset($request->attendance_id))
            $Sessions = AttendanceSession::where('attendance_id',$request->attendance_id)->get();
        if(count($Sessions) == 0)
            return HelperController::api_response_format(200, 'there is no sessions');

        foreach($Sessions as $session){
            $sess['id'] = $session->id;
            $sess['course'] = (isset($session->course_segment_id)) ? $session->Course_Segment->courses[0]->name:'-';
            $sess['class'] =  (isset($session->course_segment_id)) ? ($session->Course_Segment->segmentClasses[0]->classLevel[0]->classes[0]->name): '-';
            $sess['from'] =(isset( $session->from)) ? $session->from :'-';
            $sess['to'] = (isset( $session->to)) ? $session->to:'-';
            $sess['date'] = $session->date;
            $final[]=$sess;
        }
        if(isset($request->search))
        {
            foreach($final as $one){
                $course=strpos($one['course'], $request->search);
                if($course > -1)
                    $test[]=$one;
                    
                $class=strpos($one['class'], $request->search);
                if($class > -1)
                    $test[]=$one;

                $date=strpos($one['date'], $request->search);
                if($date > -1)
                    $test[]=$one;
            }
            return HelperController::api_response_format(200, $test);
        }
        return HelperController::api_response_format(200, $final);
    }
}
