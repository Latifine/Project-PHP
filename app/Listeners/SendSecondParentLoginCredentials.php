<?php

namespace App\Listeners;

use App\Events\SecondParentActivated;
use App\Mail\SecondParentLoginCredentials;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendSecondParentLoginCredentials implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  SecondParentActivated  $event
     * @return void
     */
    public function handle(SecondParentActivated $event)
    {
        $password = $event->password; // Access the password from the event
        Mail::to($event->secondParent->email)->send(new SecondParentLoginCredentials($password));
        \Log::info('User verified: ' . $event->secondParent->email);
        $event->secondParent->update(['is_active' => true]);
    }
}
