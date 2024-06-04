<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SecondParentActivated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $secondParent;
    public $password; // Add this property

    /**
     * Create a new event instance.
     *
     * @param User $secondParent
     * @param string $password
     * @return void
     */
    public function __construct(User $secondParent, $password)
    {
        $this->secondParent = $secondParent;
        $this->password = $password; // Assign the password
    }
}
