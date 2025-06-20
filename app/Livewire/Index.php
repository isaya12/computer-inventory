<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Device;
use App\Models\User;

class Index extends Component
{
    #[Layout('layouts.app')]
    #[Title('Dashboard')]

    public $totalDevices;
    public $assignedDevices;
    public $availableDevices;
    public $maintenanceDevices;
    public $usersCount;
    public $staffCount;
    public $recentDevices;
    public $recentAssignedDevices; // Add this line

    public function mount()
    {
        $this->totalDevices = Device::count();
        $this->assignedDevices = Device::where('status', 'assigned')->count();
        $this->availableDevices = Device::where('status', 'available')->count();
        $this->maintenanceDevices = Device::where('status', 'maintenance')->count();
        $this->usersCount = User::count();
        $this->staffCount = User::where('role','staff')->count();

        // Get 4 most recently added devices
        $this->recentDevices = Device::latest()
            ->take(4)
            ->get();

        // Get 4 most recently assigned devices
        $this->recentAssignedDevices = Device::where('status', 'assigned')
            ->latest()
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.index');
    }
}
