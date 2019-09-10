<?php

namespace App\Handlers\Events;

use App\Events;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\sessions;
use Carbon\Carbon;

class AuthLoginEventHandler
{
    /**
     * Create the event handler.
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
     * @param  Events  $event
     * @return void
     */
    public function handle(Events $event)
    {
        //
    }

    public function login()
    {
        if(\Auth::check()){
            $sessions = new sessions();
            $sessions->first_activity = Carbon::now();
            $sessions->last_activity = Carbon::now();
            $sessions->user_id = \Auth::User()->id;
            $sessions->save();
        }
    }
}
