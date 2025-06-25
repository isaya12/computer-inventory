<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MaintenanceNotification;
use App\Notifications\MaintenanceReminder;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendMaintenanceNotifications extends Command
{
    protected $signature = 'notifications:send-maintenance';
    protected $description = 'Send scheduled maintenance notifications';

    public function handle()
    {
        $notifications = MaintenanceNotification::with('schedule')
            ->where('is_sent', false)
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($notifications as $notification) {
            // Send to all users or specific recipients
            $users = User::all(); // Or define who should receive these

            Notification::send($users, new MaintenanceReminder(
                $notification->schedule,
                $notification->type,
                $notification->message
            ));

            $notification->update([
                'is_sent' => true,
                'sent_at' => now()
            ]);
        }

        $this->info('Sent '.$notifications->count().' maintenance notifications');
    }
}
