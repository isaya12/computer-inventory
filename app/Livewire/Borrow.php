<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Device;
use App\Models\BorrowDevice;
use Illuminate\Support\Facades\Auth;

class Borrow extends Component
{
    use WithFileUploads;

    public $devices = [];
    public $selectedDevice;
    public $purpose;
    public $expectedReturnDate;
    public $borrowings = [];
    public $showBorrowModal = false;
    public $showReturnModal = false;
    public $returnNotes;
    public $selectedBorrowing;

    protected $rules = [
        'selectedDevice' => 'required|exists:devices,id',
        'purpose' => 'required|string|max:500',
        'expectedReturnDate' => 'required|date|after:today'
    ];

    public function mount()
    {
        $this->loadAvailableDevices();
        $this->loadUserBorrowings();
    }

    public function loadAvailableDevices()
    {
        $this->devices = Device::where('status', 'available')->get();
    }

    public function loadUserBorrowings()
    {
        $this->borrowings = BorrowDevice::with('device')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['approved', 'overdue'])
            ->get();
    }

    public function requestBorrow()
    {
        $this->validate();

        $borrowing = BorrowDevice::create([
            'device_id' => $this->selectedDevice,
            'user_id' => Auth::id(),
            'borrowed_at' => now(),
            'expected_return_date' => $this->expectedReturnDate,
            'purpose' => $this->purpose,
            'status' => 'pending'
        ]);

        // Update device status
        Device::find($this->selectedDevice)->update(['status' => 'borrow']);

        $this->reset(['selectedDevice', 'purpose', 'expectedReturnDate', 'showBorrowModal']);
        $this->dispatch('close-modal', id: 'borrowDeviceModal');
        $this->loadUserBorrowings();
        $this->dispatch('notify', type: 'success', message: 'Borrow request submitted successfully!');
    }

    public function prepareReturn($borrowingId)
    {
        $this->selectedBorrowing = $borrowingId;
        $this->showReturnModal = true;
        $this->dispatch('open-modal', id: 'returnDeviceModal');
    }

    public function submitReturn()
    {
        $this->validate(['returnNotes' => 'required|string|max:500']);

        $borrowing = BorrowDevice::find($this->selectedBorrowing);

        $borrowing->update([
            'status' => 'returned',
            'returned_at' => now(),
            'notes' => $this->returnNotes
        ]);

        // Update device status
        $borrowing->device->update(['status' => 'available']);

        $this->reset(['selectedBorrowing', 'returnNotes', 'showReturnModal']);
        $this->dispatch('close-modal', id: 'returnDeviceModal');
        $this->loadUserBorrowings();
        $this->dispatch('notify', type: 'success', message: 'Device returned successfully!');
    }

    public function cancelBorrowRequest($borrowingId)
    {
        $borrowing = BorrowDevice::find($borrowingId);

        if ($borrowing->status === 'pending') {
            $borrowing->device->update(['status' => 'available']);
            $borrowing->delete();
            $this->loadUserBorrowings();
            $this->dispatch('notify', type: 'success', message: 'Borrow request canceled!');
        }
    }

    public function render()
    {
        return view('livewire.borrow');
    }
}
