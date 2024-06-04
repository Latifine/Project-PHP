<?php

namespace App\Listeners;

use App\Enum\EmailType;
use App\Events\MailToUsers;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\InfoToAllUsers;

class SendMailToUsers
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MailToUsers $event): void
    {
        $users = User::all();
        $object = $event->object;
        $emailType = $event->emailType;
        $subject = $event->subject ?? '';
        $description = $event->description ?? '';
        foreach ($users as $user) {
            // Ensure the email is valid and not empty
            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($user->email)->send(new InfoToAllUsers($user, $object, $emailType, $subject, $description));
            }
        }
    }
}
