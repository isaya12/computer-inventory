<?php
namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Category;

class Device extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $sortField = 'name'; // Changed from device_name to name
    public $sortDirection = 'asc';

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
        $devices =  \App\Models\Device::with('category') // Eager load the category relationship
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
            'categories' => Category::all(), // Get all categories instead of distinct device categories
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
