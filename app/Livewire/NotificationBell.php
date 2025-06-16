<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount;
    public $notifications;

    protected $listeners = ['echo:tickets,NewTicketCreated' => 'refreshNotifications'];

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $this->unreadCount = Auth::user()->unreadNotifications()->count();
        $this->notifications = Auth::user()->notifications()->take(5)->get();
    }

    public function markAsRead($notificationId)
    {
        Auth::user()->notifications()->where('id', $notificationId)->first()->markAsRead();
        $this->refreshNotifications();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
