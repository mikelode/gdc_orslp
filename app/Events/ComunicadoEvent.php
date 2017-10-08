<?php

namespace aidocs\Events;

use aidocs\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ComunicadoEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($msg)
    {
        $this->data = array(
            'msn'   =>  $msg
        );
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['notice-channel'];
    }
}
