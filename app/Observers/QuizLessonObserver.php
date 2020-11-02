<?php

namespace App\Observers;

use Modules\QuestionBank\Entities\QuizLesson;
use Modules\QuestionBank\Entities\Quiz;
use App\Lesson;
use App\Timeline;
use Carbon;

class QuizLessonObserver
{
    /**
     * Handle the quiz lesson "created" event.
     *
     * @param  \App\QuizLesson  $quizLesson
     * @return void
     */
    public function created(QuizLesson $quizLesson)
    {
        $quiz = Quiz::where('id',$quizLesson->quiz_id)->first();
        $lesson = Lesson::find($quizLesson->lesson_id);
        $course_id = $lesson->courseSegment->course_id;
        $class_id = $lesson->courseSegment->segmentClasses[0]->classLevel[0]->class_id;
        $level_id = $lesson->courseSegment->segmentClasses[0]->classLevel[0]->yearLevels[0]->level_id;
        if(isset($quiz)){
            Timeline::firstOrCreate([
                'item_id' => $quizLesson->quiz_id,
                'name' => $quiz->name,
                'start_date' => $quizLesson->start_date,
                'due_date' => $quizLesson->due_date,
                'publish_date' => isset($quizLesson->publish_date)? $quizLesson->publish_date : Carbon::now(),
                'course_id' => $course_id,
                'class_id' => $class_id,
                'lesson_id' => $quizLesson->lesson_id,
                'level_id' => $level_id,
                'type' => 'quiz'
            ]);
        }
    }

    /**
     * Handle the quiz lesson "updated" event.
     *
     * @param  \App\QuizLesson  $quizLesson
     * @return void
     */
    public function updated(QuizLesson $quizLesson)
    {
        $quiz = Quiz::where('id',$quizLesson->quiz_id)->first();
        if(isset($quiz)){
            Timeline::where('item_id',$quizLesson->quiz_id)->where('lesson_id',$quizLesson->lesson_id)->where('type' , 'quiz')
            ->update([
                'item_id' => $quizLesson->quiz_id,
                'name' => $quiz->name,
                'start_date' => $quizLesson->start_date,
                'due_date' => $quizLesson->due_date,
                'publish_date' => isset($quizLesson->publish_date)? $quizLesson->publish_date : Carbon::now(),
                'lesson_id' => $quizLesson->lesson_id,
                'type' => 'quiz',
                'visible' => $quizLesson->visible
            ]);
        }
    }

    /**
     * Handle the quiz lesson "deleted" event.
     *
     * @param  \App\QuizLesson  $quizLesson
     * @return void
     */
    public function deleted(QuizLesson $quizLesson)
    {
        Timeline::where('lesson_id',$quizLesson->lesson_id)->where('item_id',$quizLesson->quiz_id)->where('type','quiz')->delete();
    }

    /**
     * Handle the quiz lesson "restored" event.
     *
     * @param  \App\QuizLesson  $quizLesson
     * @return void
     */
    public function restored(QuizLesson $quizLesson)
    {
        //
    }

    /**
     * Handle the quiz lesson "force deleted" event.
     *
     * @param  \App\QuizLesson  $quizLesson
     * @return void
     */
    public function forceDeleted(QuizLesson $quizLesson)
    {
        //
    }
}
