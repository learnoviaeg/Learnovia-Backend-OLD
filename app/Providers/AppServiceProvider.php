<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Repositories\BackendServiceProvider;
use App\Grader\GraderServiceProvider;
use App\Grader\HighestGrade;
use App\Grader\AverageGrade;
use App\Grader\TypeGrader;
use App\Grader\LowestGrade;
use App\Grader\FirstGrade;
use App\Grader\LastGrade;
use App\GradesSetup\NaturalMethod;
use App\Enroll;
use App\Observers\EnrollObserver;
use App\GradeItems;
use App\GradeCategory;
use App\Observers\GradeItemObserver;
use App\UserGrade;
use App\UserGrader;
use App\Observers\UserGradeObserver;
use App\Observers\MaterialsObserver;
use App\h5pLesson;
use App\Observers\H5pObserver;
use App\Observers\LogsObserver;
use Modules\Attendance\Entities\AttendanceSession;
use App\Observers\UserSeenObserver;
use App\UserSeen;
use App\User;
use App\Parents;
use App\AcademicType;
use App\Announcement;
use App\Observers\Announcements;
use App\AcademicYear;
use App\Classes;
use App\Course;
use App\Level;
use App\Lesson;
use App\Segment;
// use App\YearLevel;
// use App\ClassLevel;
// use App\AcademicYearType;
use App\Observers\SecodaryChainObserver;
use App\Timeline;
use App\Material;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(BackendServiceProvider::class);
        $this->app->register(GraderServiceProvider::class);
        
        $FirstGrade = new FirstGrade();
        $this->app->instance('First', $FirstGrade);
        
        $LastGrade = new LastGrade();
        $this->app->instance('Last', $LastGrade);

        $HighestGrade = new HighestGrade();
        $this->app->instance('Highest', $HighestGrade);

        $LowestGrade = new LowestGrade();
        $this->app->instance('Lowest', $LowestGrade);
        
        $AverageGrade = new AverageGrade();
        $this->app->instance('Average', $AverageGrade);

        $NaturalMethod = new NaturalMethod();
        $this->app->instance('Natural', $NaturalMethod);
        
        $TypeGrader = new TypeGrader();
        $this->app->instance(TypeGrader::class, $TypeGrader);        
    }

    public function boot()
    {
        Schema::defaultStringLength(191);
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                array_values($this->forPage($page, $perPage)->toArray()),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        h5pLesson::observe(LogsObserver::class);

        AcademicType::observe(LogsObserver::class);
        AcademicYear::observe(LogsObserver::class);
        Classes::observe(LogsObserver::class);
        Course::observe(LogsObserver::class);
        Level::observe(LogsObserver::class);
        Lesson::observe(LogsObserver::class);
        Segment::observe(LogsObserver::class);
        // AcademicYearType::observe(LogsObserver::class);
        // ClassLevel::observe(LogsObserver::class);
        // YearLevel::observe(LogsObserver::class);
        User::observe(LogsObserver::class);
        Parents::observe(LogsObserver::class);
        // CourseSegment::observe(LogsObserver::class);
        Enroll::observe(EnrollObserver::class);

        Enroll::observe(SecodaryChainObserver::class);

        // UserGrade::observe(UserGradeObserver::class);
        GradeItems::observe(GradeItemObserver::class);
        GradeCategory::observe(LogsObserver::class);
        UserGrader::observe(LogsObserver::class);
        Announcement::observe(LogsObserver::class);
        Timeline::observe(LogsObserver::class);
        Material::observe(LogsObserver::class);
        AttendanceSession::observe(LogsObserver::class);
        Announcement::observe(Announcements::class);
        Material::observe(MaterialsObserver::class);
        UserSeen::observe(UserSeenObserver::class);
        h5pLesson::observe(H5pObserver::class);
    }
}
