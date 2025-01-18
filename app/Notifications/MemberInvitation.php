<?php

namespace App\Notifications;

use App\Facades\Auth;
use App\Models\Server;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Channels\BroadcastChannel;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;

class MemberInvitation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Server $server, protected User $to, protected ?User $from = null)
    {
        if (is_null($this->from)) {
            $this->from = Auth::user();
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            DatabaseChannel::class,
            BroadcastChannel::class,
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
            'title' => "{$this->from->name} さんからサーバメンバーの招待が届いています",
            'sender_id' => $this->from->id,
            'receiver_id' => $this->to->id,
            'server_id' => $this->server->id,
        ];
    }
}
