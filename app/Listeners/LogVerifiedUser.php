<?php

namespace App\Listeners;

use App\Events\MailToUsers;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogVerifiedUser
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Log that the user has been verified
        \Log::info('User verified: ' . $event->user->email);
        $event->user->update(['is_active' => true]);
    }
}
