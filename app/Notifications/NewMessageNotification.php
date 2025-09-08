<?php
namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;


class NewMessageNotification extends Notification implements ShouldQueue
{
use Queueable;


public function __construct(
public int $conversationId,
public int $senderId,
public string $senderName,
public string $preview
) {}


public function via(object $notifiable): array
{
return ['database', 'broadcast'];
}


public function toArray(object $notifiable): array
{
return [
'conversation_id' => $this->conversationId,
'sender_id' => $this->senderId,
'sender_name' => $this->senderName,
'message' => $this->preview,
'url' => route('chat.show', $this->conversationId),
];
}


public function toBroadcast(object $notifiable): BroadcastMessage
{
return new BroadcastMessage($this->toArray($notifiable));
}
}