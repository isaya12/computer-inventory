<div>
    <li class="nav-item dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
            <img src="assets/img/icons/notification-bing.svg" alt="img"> <span class="badge rounded-pill">4</span>
        </a>
        <div class="dropdown-menu notifications">
            <div class="topnav-dropdown-header">
                <span class="notification-title">Notifications</span>
                <a href="javascript:void(0)" class="clear-noti" wire:click="markAllAsRead"> Clear All </a>
            </div>
            <div class="noti-content">
                <ul class="notification-list" id="notification-list">
                    @foreach ($notifications as $notification)
                        <li class="notification-message">
                            <a href="" wire:click="markAsRead('{{ $notification->id }}')">
                                <div class="media d-flex">
                                    <span class=" flex-shrink-0 text-center">
                                        <img alt="" src="{{ asset('assets/img/icons/debitcard.svg') }}">
                                    </span>
                                    <div class="px-2 media-body flex-grow-1">
                                        <p class="noti-details">
                                            <span class="noti-title">New Ticket</span>
                                            from {{ $notification->data['user_name'] }}:
                                            <span class="noti-title">{{ $notification->data['ticket_subject'] }}</span>
                                        </p>
                                        <p class="noti-time">
                                            <span
                                                class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="topnav-dropdown-footer">
                <a href="">View all Notifications</a>
            </div>
        </div>
    </li>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Notification sound (using a simple beep if no file available)
            function playNotificationSound() {
                try {
                    const audio = new Audio('{{ asset('assets/audio/audio.mp3') }}');
                    audio.play().catch(e => {
                        // Fallback to browser beep if audio file fails
                        console.log('Playing fallback sound');
                        const ctx = new(window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        osc.type = 'sine';
                        osc.frequency.value = 800;
                        osc.connect(ctx.destination);
                        osc.start();
                        osc.stop(ctx.currentTime + 0.2);
                    });
                } catch (e) {
                    console.error('Sound playback failed:', e);
                }
            }

            // Listen for new notifications
            Echo.private(`App.Models.User.${@json(auth()->id())}`)
                .notification((notification) => {
                    Livewire.dispatch('refreshNotifications');
                    playNotificationSound();
                });
        });
    </script>
</div>
