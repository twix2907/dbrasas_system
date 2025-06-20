<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'pin_code' => 'required|string',
        ]);

        $user = User::where('pin_code', $request->pin_code)->first();

        if (!$user) {
            return redirect()->back()->withErrors([
                'pin_code' => 'PIN incorrecto. Intente nuevamente.',
            ]);
        }

        Auth::login($user);

        // Redireccionar según el rol
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'mesero':
                return redirect()->route('mesero.tables');
            case 'cajero':
                return redirect()->route('cajero.dashboard');
            case 'cocinero':
                return redirect()->route('cocinero.dashboard');
            default:
                return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}