<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Masmerise\Toaster\Toaster;

class NewUser extends Component
{
    use WithFileUploads;

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $image;
    public $password;
    public $password_confirmation;
    public $role = 'staff';
    public $is_active = true;

    protected $rules = [
        'first_name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[\pL\s\-]+$/u' // Allows letters, spaces, and hyphens
        ],
        'last_name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[\pL\s\-]+$/u' // Allows letters, spaces, and hyphens
        ],
        'email' => 'required|email|unique:users,email',
        'phone' => [
            'required',
            'numeric',
            'digits:10',
            'regex:/^(07|06)\d{8}$/'
        ],
        'image' => 'nullable|image|max:2048',
        'role' => 'required|in:admin,it-person,staff',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'first_name.regex' => 'The first name may only contain letters, spaces, and hyphens.',
        'last_name.regex' => 'The last name may only contain letters, spaces, and hyphens.',
        'phone.regex' => 'The phone number must start with 07 or 06 and be 10 digits long.',
        'phone.digits' => 'The phone number must be exactly 10 digits.',
    ];

    public function render()
    {
        return view('livewire.new-user')
            ->layout('layouts.app');
    }

    public function save()
    {
        $this->validate();

        // Handle image upload
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('profile-photos', 'public');
        }

        // Create user
        User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $imagePath,
            'password' => Hash::make('hello'),
            'role' => $this->role,
            'is_active' => $this->is_active,
            'is_banned' => false // Default to not banned
        ]);

        Toaster::success('User created successfully!');
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset([
            'first_name',
            'last_name',
            'email',
            'phone',
            'image',
            'password',
            'role',
            'is_active'
        ]);
        $this->role = 'staff';
        $this->is_active = true;
    }
}
