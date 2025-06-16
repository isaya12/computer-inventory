<?php
namespace App\Notifications;

use App\Models\Tickets;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct(Tickets $ticket)
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
            'ticket_subject' => $this->ticket->subject,
            'user_name' => optional($this->ticket->user)->first_name ?? 'Unknown User',
            'message' => 'New ticket created: '.$this->ticket->subject,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'ticket_id' => $this->ticket->id,
            'ticket_subject' => $this->ticket->subject,
            'user_name' => optional($this->ticket->user)->first_name ?? 'Unknown User',
            'message' => 'New ticket created: '.$this->ticket->subject,
        ]);
    }
}
