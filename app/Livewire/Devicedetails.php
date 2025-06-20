<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Device;
use App\Models\Category;
use App\Models\User;
use App\Models\Location;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Devicedetails extends Component
{
    use WithFileUploads;

    public $device;
    public $activeTab = 'view';
    public $categories;
    public $users;
    public $locations;
    public $image;
    public $isUploading = false;

    // For edit form
    public $name;
    public $model;
    public $brand;
    public $category_id;
    public $serial_number;
    public $description;
    public $purchase_date;
    public $status;
    public $assigned_to;
    public $location_id;

    public function mount($id)
    {
        $this->device = Device::with(['category', 'assignedTO', 'location'])->findOrFail($id);
        $this->categories = Category::all();
        $this->users = User::all();
        $this->locations = Location::all();

        // Initialize form fields
        $this->initializeForm();
    }

    protected function initializeForm()
    {
        $this->name = $this->device->name;
        $this->model = $this->device->model;
        $this->brand = $this->device->brand;
        $this->category_id = $this->device->category_id;
        $this->serial_number = $this->device->serial_number;
        $this->description = $this->device->description;
        $this->purchase_date = $this->device->purchase_date;
        $this->status = $this->device->status;
        $this->assigned_to = $this->device->assigned_to;
        $this->location_id = $this->device->location_id;
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'edit') {
            $this->initializeForm();
        }
    }

    public function updateDevice()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'serial_number' => 'required|string|max:255|unique:devices,serial_number,'.$this->device->id,
            'description' => 'nullable|string',
            'purchase_date' => 'required|date',
            'status' => 'required|in:available,assigned,maintenance,retired',
            'assigned_to' => 'nullable|exists:users,id',
            'location_id' => 'nullable|exists:locations,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($this->image) {
            $this->isUploading = true;
            $path = $this->image->store('device-images', 'public');
            $validated['device_image'] = $path;

            // Delete old image if exists
            if ($this->device->device_image) {
                Storage::disk('public')->delete($this->device->device_image);
            }
        }

        $this->device->update($validated);

        $this->isUploading = false;
        $this->activeTab = 'view';
        $this->dispatch('deviceUpdated');
        session()->flash('message', 'Device updated successfully!');
    }

    public function render()
    {
        return view('livewire.devicedetails');
    }
}
