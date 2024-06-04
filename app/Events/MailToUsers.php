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

class MailToUsers
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $object;
    public $subject;
    public $description;
    public EmailType $emailType;


    /**
     * Create a new event instance.
     */
    public function __construct($object, EmailType $emailType, $subject=null, $description=null)
    {
        $this->object = $object;
        $this->subject = $subject;
        $this->description = $description;
        $this->emailType = $emailType;
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
