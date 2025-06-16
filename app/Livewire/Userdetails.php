<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Hash;

class Userdetails extends Component
{
    public $user;
    public $showDeleteModal = false;
    public $userIdBeingDeleted;
    public $emailSubject;
    public $emailMessage;

    public function mount($id)
    {
        $this->user = User::findOrFail($id);
    }

    public function confirmDelete($userId)
    {
        $this->userIdBeingDeleted = $userId;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->userIdBeingDeleted);
        $user->delete();

        $this->showDeleteModal = false;
        session()->flash('message', 'User deleted successfully.');

        return redirect()->route('users');
    }

    public function closeModal()
    {
        $this->showDeleteModal = false;
    }

    public function banUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_banned' => true]);
        session()->flash('message', 'User has been banned.');
        $this->mount($userId); // Refresh the user data
    }

    public function unbanUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_banned' => false]);
        session()->flash('message', 'User has been unbanned.');
        $this->mount($userId); // Refresh the user data
    }

    public function resetPassword($userId)
    {
        $user = User::findOrFail($userId);
        $tempPassword = str_random(8);
        $user->update(['password' => Hash::make($tempPassword)]);

        // Send email with new password
        Mail::to($user->email)->send(new UserNotification(
            'Password Reset',
            "Your password has been reset. Your temporary password is: $tempPassword"
        ));

        session()->flash('message', 'Password has been reset and emailed to the user.');
    }

    public function sendEmail()
    {
        $validatedData = $this->validate([
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
        $validatedData = $this->validate([
            'user.first_name' => 'required|string|max:255',
            'user.last_name' => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email,'.$this->user->id,
            'user.phone' => 'nullable|string|max:20',
            'user.role' => 'required|in:admin,staff,it-person,user',
        ]);

        $this->user->save();
        session()->flash('message', 'User updated successfully.');
    }

    public function render()
    {
        return view('livewire.userdetails', [
            'user' => $this->user
        ]);
    }
}
