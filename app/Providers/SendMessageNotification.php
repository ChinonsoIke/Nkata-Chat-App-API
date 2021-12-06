<?php

namespace App\Providers;

use App\Providers\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageNotification
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
     * @param  \App\Providers\MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        //
    }
}
