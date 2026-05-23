<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;


class NewTicketNotification extends Notification implements ShouldBroadcast
{
    
    private $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'message' => 'Ticket baru dibuat oleh '.$this->ticket->nama,
            'url' => '/admin/ticket/edit/'.$this->ticket->id
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'ticket_id' => $this->ticket->id,
            'message' => 'Ticket baru dibuat oleh '.$this->ticket->nama,
            'url' => '/admin/ticket/edit/'.$this->ticket->id
        ]);
    }
}