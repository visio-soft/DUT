<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login page
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            Session::flash('success', 'Başarıyla giriş yaptınız!');
            
            // Check if user has redirect intended
            $intended = session()->pull('url.intended', route('public.projects.index'));
            
            return redirect($intended);
        }

        throw ValidationException::withMessages([
            'email' => 'Email adresi veya şifre hatalı.',
        ]);
    }

    /**
     * Show the registration page
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'İsim alanı gereklidir.',
            'name.min' => 'İsim en az 2 karakter olmalıdır.',
            'name.max' => 'İsim en fazla 255 karakter olabilir.',
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'email.unique' => 'Bu email adresi zaten kullanılıyor.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(), // Auto-verify for simplicity
        ]);

        // Assign default user role if you have role system
        try {
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('user'); // Default role
            }
        } catch (\Exception $e) {
            // If role doesn't exist, continue without error
        }

        Auth::login($user);

        Session::flash('success', 'Hesabınız başarıyla oluşturuldu! Hoş geldiniz!');

        return redirect()->route('public.projects.index');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Session::flash('success', 'Başarıyla çıkış yaptınız.');

        return redirect()->route('home');
    }
}
