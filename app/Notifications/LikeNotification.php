<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class LikeNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $channelName;
    protected $post;
    protected $user;

    public function __construct($post, $user)
    {
        $this->post = $post;
        $this->user = $user;
        $this->channelName = 'post.like.user.' . $this->$post->user_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

   
    /**
     * Get the broadcast channel the notification should go to.
     */
    public function broadcastOn(): Channel
    {
        // Customize the channel name
        return new PrivateChannel($this->channelName);
    }

    /**
     * Get the broadcastable data.
     */
    public function broadcastWith()
    {
        return [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'message' => $this->user->name . " likes your post: " . substr($this->post->barta, 0, 10) . "...",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'message' => $this->user->name." likes your post: ". substr($this->post->barta, 0, 10)."...",
        ];
    }


}
