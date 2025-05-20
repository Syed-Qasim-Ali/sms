<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $truckOwnerId;
    /**
     * Create a new event instance.
     */
    public function __construct($order, $truckOwnerId)
    {
        $this->order = $order;
        $this->truckOwnerId = $truckOwnerId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('orders.' . $this->truckOwnerId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => '🚚 New Order Assigned!',
            'order' => $this->order
        ];
    }
}
