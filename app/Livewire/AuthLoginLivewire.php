<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AuthLoginLivewire extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            return redirect()->route('app.home');
        }

        session()->flash('error', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth-login-livewire');
    }
}
