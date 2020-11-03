<?php

namespace App\Observers;

use Modules\UploadFiles\Entities\MediaLesson;
use Modules\UploadFiles\Entities\Media;
use App\Lesson;
use App\Material;

class MediaLessonObserver
{
    /**
     * Handle the media lesson "created" event.
     *
     * @param  \App\MediaLesson  $mediaLesson
     * @return void
     */
    public function created(MediaLesson $mediaLesson)
    {
        $media = Media::where('id',$mediaLesson->media_id)->first();
        $lesson = Lesson::find($mediaLesson->lesson_id);
        $course_id = $lesson->courseSegment->course_id;
        if(isset($media)){
            Material::firstOrCreate([
                'item_id' => $mediaLesson->media_id,
                'name' => $media->name,
                'publish_date' => $mediaLesson->publish_date,
                'course_id' => $course_id,
                'lesson_id' => $mediaLesson->lesson_id,
                'type' => 'media',
                'visible' => 1,
                'link' => $media->link
            ]);
        }
    }

    /**
     * Handle the media lesson "updated" event.
     *
     * @param  \App\MediaLesson  $mediaLesson
     * @return void
     */
    public function updated(MediaLesson $mediaLesson)
    {
        $media = Media::where('id',$mediaLesson->media_id)->first();
        if(isset($media)){
            Material::where('item_id',$mediaLesson->media_id)->where('lesson_id',$mediaLesson->lesson_id)->where('type' , 'media')
            ->update([
                'item_id' => $mediaLesson->media_id,
                'name' => $media->name,
                'publish_date' => $mediaLesson->publish_date,
                'lesson_id' => $mediaLesson->lesson_id,
                'type' => 'media',
                'visible' => $mediaLesson->visible,
                'link' => $media->link
            ]);
        }
    }

    /**
     * Handle the media lesson "deleted" event.
     *
     * @param  \App\MediaLesson  $mediaLesson
     * @return void
     */
    public function deleted(MediaLesson $mediaLesson)
    {
        Material::where('lesson_id',$mediaLesson->lesson_id)->where('item_id',$mediaLesson->media_id)->where('type','media')->delete();
    }

    /**
     * Handle the media lesson "restored" event.
     *
     * @param  \App\MediaLesson  $mediaLesson
     * @return void
     */
    public function restored(MediaLesson $mediaLesson)
    {
        //
    }

    /**
     * Handle the media lesson "force deleted" event.
     *
     * @param  \App\MediaLesson  $mediaLesson
     * @return void
     */
    public function forceDeleted(MediaLesson $mediaLesson)
    {
        //
    }
}
