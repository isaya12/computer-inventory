<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Device;
use App\Models\Category;
use App\Models\User;
use App\Models\Location;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Newdevice extends Component
{
    use WithFileUploads;

    public $name;
    public $model;
    public $brand;
    public $category_id;
    public $serial_number;
    public $description;
    public $purchase_date;
    public $barcode;
    public $assigned_to;
    public $location_id;
    public $device_image;

    public $categories;
    public $users;


    protected $rules = [
        'name' => 'required|string|max:255',
        'model' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
        'serial_number' => 'required|string|unique:devices,serial_number',
        'description' => 'nullable|string',
        'purchase_date' => 'required|date',
        'barcode' => 'nullable|string|unique:devices,barcode',
        'device_image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->categories = Category::all();
        $this->users = User::where('is_active', true)->get();
        $this->purchase_date = now()->format('Y-m-d');
    }

    public function generateBarcode()
{
    $this->barcode = 'DEV-' . Str::upper(Str::random(8));
    $this->dispatch('generateClientBarcode', barcode: $this->barcode)
        ->to(Newdevice::class); // Explicitly target the component
}

    public function save()
    {
        $this->validate();

        // Handle image upload
        $imagePath = null;
        if ($this->device_image) {
            $imagePath = $this->device_image->store('device-images', 'public');
        }

        // Create device
        Device::create([
            'name' => $this->name,
            'model' => $this->model,
            'brand' => $this->brand,
            'category_id' => $this->category_id,
            'serial_number' => $this->serial_number,
            'description' => $this->description,
            'purchase_date' => $this->purchase_date,
            'barcode' => $this->barcode,
            'image' => $imagePath,
        ]);

        session()->flash('success', 'Device added successfully!');
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset([
            'name', 'model', 'brand', 'category_id', 'serial_number',
            'description', 'purchase_date','barcode', 'device_image'
        ]);
        $this->quantity = 1;
        $this->status = 'available';
        $this->purchase_date = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.newdevice')
            ->layout('layouts.app');
    }
}
