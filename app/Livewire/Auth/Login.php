<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';

    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required'    => 'Ingresa tu correo.',
        'email.email'       => 'Formato de correo no válido.',
        'password.required' => 'Ingresa tu contraseña.',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();

            $role = Auth::user()->role;

            // Redirección inteligente según el rol
            return match ($role) {
                'admin'       => redirect()->intended('/admin/productos'),
                'supervisor'  => redirect()->intended('/almacen'),
                'solicitante' => redirect()->intended('/'),
                default       => redirect('/'),
            };
        }

        $this->addError('email', 'Las credenciales proporcionadas no son correctas.');
    }

        public function render()
        {
            return view('livewire.auth.login')
                ->layout('components.layouts.app');
        }
}