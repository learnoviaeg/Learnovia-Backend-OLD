<?php

namespace App\Jobs;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Installment;
use App\Repositories\ChainRepositoryInterface;
use App\Repositories\NotificationRepoInterface;
use Carbon\Carbon;
use App\Parents;
use App\NotificationSetting;

class FeesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $installment;
    public $chain;
    public $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Installment $installment , $chain , $notification)
    {
        $this->installment = $installment;
        $this->chain = $chain;
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(Installment::count() > 0 && (bool) $this->installment->notified == false){

            // $notification_settings =  NotificationSetting::select('after_min')->where('type' , 'fees')->first();
    
            // if(isset($notification_settings->after_min))
            //     $notification_settings_days = $notification_settings->after_min;


            $notification_settings =  NotificationSetting::select('after_min')->where('type' , 'fees')->first();
            if(isset($notification_settings))
                $notification_settings_days = $notification_settings->after_min;
    
            if((isset($notification_settings) && $notification_settings->after_min > 0) )
            {    
                $notification_date = Carbon::parse($this->installment->date)->subDays($notification_settings_days);
                if($notification_date->isToday() )
                {
                    $reqNot=[
                        'message' => 'Fees is due by '.$this->installment->date ,
                        'item_id' => $this->installment->id,
                        'item_type' => 'fees',
                        'type' => 'notification',
                        'publish_date' => Carbon::now()->format('Y-m-d H:i:s'),
                    ];
                    $Installment_percentage = Installment::where('date' , '<=' , Carbon::parse($this->installment->date)->format('Y-m-d'))->sum('percentage');
                    $students = $this->chain->getEnrollsByManyChain(new Request())->select('user_id')->distinct('user_id')->where('role_id', 3)
                                ->whereHas('user.fees',function($q) use ($Installment_percentage){  $q->where('percentage', '>', $Installment_percentage );  })->pluck('user_id');

                    $users = Parents::select('parent_id')->distinct('parent_id')->whereIn('child_id', $students)->pluck('parent_id');
                    if($users->count() > 0)
                        $this->notification->sendNotify($users->toArray(),$reqNot);
                    
                    Installment::where('id',$this->installment->id)->update(['notified' => 1]);
                }
            }
        }
    }
}
