<?php

namespace App\Events;

use App\Enum\EmailType;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MailToUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $object;
    public EmailType $emailType;
    public $subject;
    public $description;


    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $object, EmailType $emailType, $subject=null, $description=null)
    {
        $this->user_id = $user_id;
        $this->object = $object;
        $this->emailType = $emailType;
        $this->subject = $subject;
        $this->description = $description;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
