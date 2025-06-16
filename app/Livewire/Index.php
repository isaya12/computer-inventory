<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Device;

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

        // Get 4 most recently added devices
        $this->recentDevices = Device::latest()
            ->take(4)
            ->get();

        // Get 4 most recently assigned devices
        $this->recentAssignedDevices = Device::where('status', 'assigned')
            ->latest()
            ->take(4)
            ->get();

        // If you have User and Staff models, uncomment these:
        // $this->usersCount = \App\Models\User::count();
        // $this->staffCount = \App\Models\Staff::count();
    }

    public function render()
    {
        return view('livewire.index');
    }
}
