<?php
namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Category;
use App\Models\User;

class Device extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $sortField = 'name'; // Changed from device_name to name
    public $sortDirection = 'asc';
    public $assignDeviceId = null;
    public $userId = null;
    public $showAssignModal = false;


    public function assignDevice($deviceId)
    {
        $this->assignDeviceId = $deviceId;
        $this->showAssignModal = true;
    }

    public function completeAssignment()
    {
        $this->validate([
            'userId' => 'required|exists:users,id',
        ]);

        $device =  \App\Models\Device::find($this->assignDeviceId);
        $device->update([
            'status' => 'assigned',
            'assigned_to' => $this->userId,
            'assigned_at' => now(),
        ]);

        $this->reset(['assignDeviceId', 'userId', 'showAssignModal']);
        session()->flash('message', 'Device assigned successfully.');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function setStatusFilter($status)
{
    $this->statusFilter = $status;
    $this->resetPage(); // Reset pagination when changing filters
}

    public function render()
    {
        $devices =  \App\Models\Device::with('category', 'assignedTo') // Eager load the category relationship
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('serial_number', 'like', '%'.$this->search.'%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->whereHas('category', function($q) {
                    $q->where('id', $this->categoryFilter);
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest()
            ->paginate(10);

        return view('livewire.device', [
            'devices' => $devices,
            'categories' => Category::all(),
            'users' => User::all(),
        ]);
    }

    public function deleteDevice($id)
    {
        Device::find($id)->delete();
        session()->flash('message', 'Device deleted successfully.');
    }

    public function viewDevice($id)
{
    return redirect()->route('devicedatails', $id);
}
}
