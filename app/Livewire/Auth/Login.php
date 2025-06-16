<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    #[Layout('layouts.auth.app')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function login()
    {
        $validated = $this->validate();

        // First attempt to authenticate
        if (!Auth::attempt($validated, $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Check if the authenticated user is banned
        if (Auth::user()->is_banned) {
            Auth::logout(); // Immediately log them out if banned

            throw ValidationException::withMessages([
                'email' => 'Your account has been banned. Please contact support.',
            ]);
        }

        // Check if the user is active (optional)
        if (!Auth::user()->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Your account is inactive. Please contact support.',
            ]);
        }

        return redirect()->intended(route('home'));
    }
}
