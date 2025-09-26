<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    /**
     * Giriş sayfasını göster
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    /**
     * Kayıt sayfasını göster
     */
    public function showRegisterForm()
    {
        return view('user.auth.register');
    }

    /**
     * Kullanıcı girişi
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'E-posta adresi gereklidir.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'password.required' => 'Şifre gereklidir.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('user.index'))
                ->with('success', 'Başarıyla giriş yaptınız.');
        }

        throw ValidationException::withMessages([
            'email' => 'Girilen bilgiler hatalı.',
        ]);
    }

    /**
     * Kullanıcı kaydı
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Ad soyad gereklidir.',
            'name.max' => 'Ad soyad en fazla 255 karakter olabilir.',
            'email.required' => 'E-posta adresi gereklidir.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kayıtlı.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto-verify for user panel
        ]);

        // Assign 'user' role to new registrations
        $user->assignRole('user');

        Auth::login($user);

        return redirect()->route('user.index')
            ->with('success', 'Hesabınız başarıyla oluşturuldu.');
    }

    /**
     * Kullanıcı çıkışı
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.index')
            ->with('success', 'Başarıyla çıkış yaptınız.');
    }
}
