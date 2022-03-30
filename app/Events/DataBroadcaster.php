<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataBroadcaster implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private array | null $arrayData;
    private string $deviceName;
    private string | null $error;
    private bool $isFinished;
    public $broadcastQueue = 'broadcast-queue';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array | null $arrayData, string $deviceName, string | null $error, bool $isFinished)
    {
        $this->arrayData = $arrayData;
        $this->deviceName = $deviceName;
        $this->error = $error;
        $this->isFinished = $isFinished;
    }

    public function broadcastWith() {
        if ($this->isFinished) {
            return [
                'finished' => true
            ];
        } else {
            if ($this->error) {
                return [
                    'error' => $this->error
                ];
            } else 
                return [
                    'data' => $this->arrayData
                ];
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->deviceName);
    }
}
