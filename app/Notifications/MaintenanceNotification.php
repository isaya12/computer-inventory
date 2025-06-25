<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $maintenance;
    public $type;

    public function __construct($maintenance, $type)
    {
        $this->maintenance = $maintenance;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Send via email and save to database
    }

    public function toMail($notifiable)
    {
        $subject = $this->getSubject();
        $message = $this->getMessage();

        return (new MailMessage)
            ->subject($subject)
            ->line($message)
            ->action('View Maintenance', url('/maintenance/'.$this->maintenance->id));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'maintenance_id' => $this->maintenance->id,
            'title' => $this->maintenance->title,
            'message' => $this->getMessage(),
        ];
    }

    protected function getSubject()
    {
        return match($this->type) {
            'reminder' => 'Maintenance Reminder: ' . $this->maintenance->title,
            'start' => 'Maintenance Started: ' . $this->maintenance->title,
            'end' => 'Maintenance Completed: ' . $this->maintenance->title,
            'cancellation' => 'Maintenance Cancelled: ' . $this->maintenance->title,
            default => 'Maintenance Notification'
        };
    }

    protected function getMessage()
    {
        return match($this->type) {
            'reminder' => 'This is a reminder that maintenance "'.$this->maintenance->title.'" is scheduled.',
            'start' => 'Maintenance "'.$this->maintenance->title.'" has started.',
            'end' => 'Maintenance "'.$this->maintenance->title.'" has been completed.',
            'cancellation' => 'Maintenance "'.$this->maintenance->title.'" has been cancelled.',
            default => 'Maintenance notification'
        };
    }
}
