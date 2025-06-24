<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class Userdetails extends Component
{
    use WithFileUploads;

    // User properties
    public $user;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $role;
    public $department;
    public $bio;

    // Component state
    public $showDeleteModal = false;
    public $userIdBeingDeleted;
    public $emailSubject;
    public $emailMessage;
    public $activeTab = 'info';
    public $photo;
    public $new_password;
    public $new_password_confirmation;
    public $editingUser = false;

    protected $queryString = ['activeTab'];

    public function mount($id)
    {
        $this->user = User::findOrFail($id);
        $this->activeTab = request()->query('activeTab', 'info');
        $this->initializeFormFields();
        $this->userIdBeingDeleted = $id;
    }

    protected function initializeFormFields()
    {
        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->role = $this->user->role;
        $this->department = $this->user->department;
        $this->bio = $this->user->bio;
    }

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function confirmDelete($userId)
    {
        $this->userIdBeingDeleted = $userId;
        $this->dispatch('show-delete-modal'); // Dispatch event to show modal
    }

    public function deleteUser()
    {
        try {
            $user = User::findOrFail($this->userIdBeingDeleted);
            $user->delete();

            $this->dispatch('hide-delete-modal');
            session()->flash('message', 'User deleted successfully.');
            return redirect()->route('users');
        } catch (\Exception $e) {
            $this->dispatch('hide-delete-modal');
            session()->flash('error', 'Failed to delete user: '.$e->getMessage());
        }
    }
    public function closeModal()
    {
        $this->showDeleteModal = false;
    }

    public function banUser()
{
    try {
        $this->user->update(['is_banned' => true]);
        $this->user->refresh(); // Force refresh
        session()->flash('message', 'User has been banned successfully.');
        $this->dispatch('user-updated'); // Refresh the UI
    } catch (\Exception $e) {
        session()->flash('error', 'Failed to ban user: '.$e->getMessage());
    }
}

public function unbanUser()
{
    try {
        $this->user->update(['is_banned' => false]);
        $this->user->refresh(); // Force refresh
        session()->flash('message', 'User has been unbanned successfully.');
        $this->dispatch('user-updated'); // Refresh the UI
    } catch (\Exception $e) {
        session()->flash('error', 'Failed to unban user: '.$e->getMessage());
    }
}

    public function sendEmail()
    {
        $this->validate([
            'emailSubject' => 'required|string|max:255',
            'emailMessage' => 'required|string',
        ]);

        Mail::to($this->user->email)->send(new UserNotification(
            $this->emailSubject,
            $this->emailMessage
        ));

        session()->flash('message', 'Email has been sent successfully.');
        $this->reset(['emailSubject', 'emailMessage']);
    }

    public function updateUser()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff,it-person,user',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
        ]);

        $this->user->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'department' => $this->department,
            'bio' => $this->bio,
        ]);

        session()->flash('message', 'User updated successfully.');
        $this->editingUser = false;
    }

    public function updateProfilePhoto()
{
    $this->validate(['photo' => 'image|max:2048']);

    try {
        if ($this->user->image) {
            Storage::delete($this->user->image);
        }

        $filename = $this->photo->store('profile-photos', 'public');
        $this->user->update(['image' => $filename]);

        $this->dispatch('close-modal', ['id' => 'uploadPhotoModal']);
        session()->flash('message', 'Profile photo updated successfully.');
        $this->user->refresh();
    } catch (\Exception $e) {
        session()->flash('error', 'Failed to update photo: '.$e->getMessage());
    }
}

    public function cancelEdit()
    {
        $this->initializeFormFields();
        $this->editingUser = false;
    }

    public function render()
    {
        return view('livewire.userdetails');
    }
}
