<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $schedule;
    public $type;
    public $message;

    public function __construct($schedule, $type, $message)
    {
        $this->schedule = $schedule;
        $this->type = $type;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->line($this->message)
            ->action('View Maintenance', url('/maintenance/'.$this->schedule->id));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'maintenance_id' => $this->schedule->id,
            'maintenance_title' => $this->schedule->title,
        ];
    }

    protected function getSubject()
    {
        return match($this->type) {
            'reminder' => 'Maintenance Reminder: ' . $this->schedule->title,
            'start' => 'Maintenance Started: ' . $this->schedule->title,
            'end' => 'Maintenance Completed: ' . $this->schedule->title,
            'cancellation' => 'Maintenance Cancelled: ' . $this->schedule->title,
            default => 'Maintenance Notification'
        };
    }
}
