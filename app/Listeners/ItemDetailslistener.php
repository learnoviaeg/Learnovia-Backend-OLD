<?php

namespace App\Listeners;

use App\Events\GradeItemEvent;
use App\GradeItems;
use App\ItemDetail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ItemDetailslistener
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
     * @param  GradeItemEvent  $event
     * @return void
     */
    public function handle(GradeItemEvent $event)
    {
        // $event->grade_item is quiz (type=>quiz)
        $grade_item=GradeItems::where('item_id',$event->grade_item->id)->where('type',$event->type)->first();
        if($event->type == 'Quiz')
            foreach($event->grade_item->Question as $question)
                ItemDetail::firstOrCreate([
                    'type' => 'Question',
                    'item_id' => $question->id,
                    'parent_item_id' => $grade_item->id,
                    // 'weight_details' => $question['mark'],
                ]);

        elseif($event->type == 'Assignment')
            ItemDetail::firstOrCreate([
                'type' => $event->type,
                'item_id' => $grade_item->item_id,
                'parent_item_id' => $grade_item->id,
                'weight_details' => json_encode($event->grade_item->assignmentLessson[0]->mark),
            ]);
    }
}
