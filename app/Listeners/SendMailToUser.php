<?php

namespace App\Listeners;

use App\Events\MailToUser;
use App\Mail\InfoToUser;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailToUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all children with their parent.
     */
    public function getAllChildrenWithParentEmails()
    {
        $childrenWithParentEmails = User::join('parents_per_child', 'users.id', '=', 'parents_per_child.user_child_id')
            ->leftJoin('users as parents', 'parents_per_child.user_parent_id', '=', 'parents.id')
            ->select(
                'users.id as user_id',
                'parents.email as parent_email'
            )
            ->orderBy('users.id')
            ->get();

        return $childrenWithParentEmails;
    }

    /**
     * Get the parent email if the user is a child.
     */
    public function getParentEmailIfChild($userId)
    {
        $children = $this->getAllChildrenWithParentEmails();
        foreach ($children as $child) {
            if ($child->user_id == $userId) {
                return $child->parent_email;
            }
        }
        return false;
    }


    /**
     * Handle the event.
     */
    public function handle(MailToUser $event): void
    {
        $user_id = $event->user_id;
        $user = User::findOrFail($user_id);
        $object = $event->object;
        $emailType = $event->emailType;
        $subject = $event->subject ?? '';
        $description = $event->description ?? '';
        if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($user->email)->send(new InfoToUser($user, $object, $emailType, $subject, $description));
        } else {
            $parentEmail = $this->getParentEmailIfChild($user_id);
            if (filter_var($parentEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::to($parentEmail)->send(new InfoToUser($user, $object, $emailType, $subject, $description));
            }
        }
    }
}
