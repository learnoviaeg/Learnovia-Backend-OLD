<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class deletedLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->req=$request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::create([
            'user' => User::find(Auth::id())->username,
            'action' => 'deleted',
            'model' => substr(get_class($this->req),strripos(get_class($this->req),'\\')+1),
            'data' => serialize($this->req),
        ]);
    }
}
