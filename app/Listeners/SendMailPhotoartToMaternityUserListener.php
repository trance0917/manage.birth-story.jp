<?php

namespace App\Listeners;

use App\Events\SendMailPhotoartToMaternityUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailPhotoartToMaternityUserListener
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
     * @param  SendMailPhotoartToMaternityUserEvent  $event
     * @return void
     */
    public function handle(SendMailPhotoartToMaternityUserEvent $event)
    {
        Mail::send(new \App\Mails\SendMailPhotoartToMaternityUserMail($event->mst_maternity_user,$event->tbl_patient));
    }
}
