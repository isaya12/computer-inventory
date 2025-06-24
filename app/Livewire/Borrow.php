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

        // For admin/IT, load pending requests separately
        if (in_array(auth()->user()->role, ['admin', 'it-person'])) {
            $this->pendingRequests = BorrowDevice::with(['device', 'user'])
                ->where('status', 'pending')
                ->get();
        }
    }

    public function loadAvailableDevices()
    {
        $this->devices = Device::where('status', 'available')->get();
    }

    public function loadUserBorrowings()
    {
        if (in_array(auth()->user()->role, ['admin', 'it-person'])) {
            // Admin/IT can see all borrowings except pending ones
            $this->borrowings = BorrowDevice::with(['device', 'user'])
                ->whereIn('status', ['approved', 'overdue', 'returned'])
                ->get();
        } else {
            // Regular users only see their own borrowings
            $this->borrowings = BorrowDevice::with('device')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['approved', 'overdue'])
                ->get();
        }
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
        $this->selectedBorrowing = BorrowDevice::with('device')->find($borrowingId);
        $this->showReturnModal = true;
        $this->dispatch('open-modal', id: 'returnDeviceModal');
    }

    public function submitReturn()
    {
        $this->validate(['returnNotes' => 'required|string|max:500']);

        $this->selectedBorrowing->update([
            'status' => 'returned',
            'returned_at' => now(),
            'notes' => $this->returnNotes
        ]);

        // Update device status
        $this->selectedBorrowing->device->update(['status' => 'available']);

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
    public function approveBorrowRequest($borrowingId)
{
    $borrowing = BorrowDevice::findOrFail($borrowingId);

    if ($borrowing->status === 'pending') {
        // Update the borrowing record
        $borrowing->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'borrowed_at' => now()
        ]);

        // Update device status to 'borrowed'
        $borrowing->device->update(['status' => 'borrow']);

        // Refresh the data
        $this->loadUserBorrowings();

        // Notify user
        $this->dispatch('notify',
            type: 'success',
            message: 'Borrow request approved successfully!'
        );
    }

}



public function viewBorrowing($borrowingId)
{
    $this->selectedBorrowing = BorrowDevice::with('device')->find($borrowingId);
    $this->dispatch('open-modal', id: 'viewBorrowingModal');
}

    public function render()
    {
        return view('livewire.borrow');
    }
}
