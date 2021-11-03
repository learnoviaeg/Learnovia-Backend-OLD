<?php

namespace App\Listeners;

use App\Events\UpdatedQuizQuestionsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\GradeCategory;
use App\ItemDetail;
use App\GradeItems;
use Modules\QuestionBank\Entities\quiz_questions;

class updateWeightDetailsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UpdatedQuizQuestionsEvent  $event
     * @return void
     */
    public function handle(UpdatedQuizQuestionsEvent $event)
    {
        foreach(quiz_questions::where('quiz_id', $event->Quiz)->cursor() as $question){
            if(is_null($question['grade_details']))
                continue;

            $grade_cat_ids=GradeCategory::where('instance_type','Quiz')->where('instance_id',$event->Quiz)->pluck('id');
            $gradeitemIDS=GradeItems::whereIn('grade_category_id',$grade_cat_ids)->pluck('id');
            if(count($gradeitemIDS) > 0){
                $item_detail=ItemDetail::whereIn('parent_item_id',$gradeitemIDS)->where('item_id',$question['question_id'])->where('type','Question')->first();
                $item_detail->weight_details=json_encode($question['grade_details']);
                $item_detail->save();
            }
        }
    }
}