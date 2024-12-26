<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostLikeEvent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $channelName;
    /**
     * Create a new event instance.
     */
    public function __construct(public $post)
    {
        $this->channelName = "post.like.userid.".$post->user->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel($this->channelName),
        ];
    }

    public function broadcastWith(): array
    {
        $formatMessage = $this->post->user->name.' liked your post: "'. substr($this->post->barta, 0, 10).'..."';

        return ['message' => $formatMessage];
    }
}
