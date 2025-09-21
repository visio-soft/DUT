@extends('user.layout')

@section('title', 'Giriş Yap - DUT Vote')

@section('content')
<div class="min-h-screen flex items-center justify-center section-padding" style="padding-top: 2rem; padding-bottom: 2rem;">
    <div class="user-container" style="max-width: 480px;">
        <div class="user-card">
            <!-- Header -->
            <div class="user-card-header text-center">
                <h1 class="user-card-title" style="font-size: 1.75rem; margin-bottom: 0.5rem;">
                    Giriş Yap
                </h1>
                <p style="color: var(--gray-600); font-size: 0.95rem;">
                    Hesabınıza giriş yaparak platformun tüm özelliklerinden yararlanın
                </p>
            </div>

            <!-- Login Form -->
            <div class="user-card-content">
                @if ($errors->any())
                    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                        <div style="font-weight: 500; margin-bottom: 0.5rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; display: inline; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Hata
                        </div>
                        <ul style="margin: 0; padding-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div style="background: var(--green-50); border: 1px solid var(--green-200); color: var(--green-800); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                        <div style="font-weight: 500;">
                            <svg style="width: 1.25rem; height: 1.25rem; display: inline; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('user.login') }}" style="space-y: 1.5rem;">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" style="display: block; font-weight: 500; color: var(--gray-700); margin-bottom: 0.5rem;">
                            E-posta Adresi
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--green-200); border-radius: var(--radius-md); font-size: 0.95rem; transition: var(--transition-normal); background: white;"
                            placeholder="ornek@email.com"
                            onfocus="this.style.borderColor='var(--green-400)'; this.style.boxShadow='var(--green-shadow)'"
                            onblur="this.style.borderColor='var(--green-200)'; this.style.boxShadow='none'"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" style="display: block; font-weight: 500; color: var(--gray-700); margin-bottom: 0.5rem;">
                            Şifre
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--green-200); border-radius: var(--radius-md); font-size: 0.95rem; transition: var(--transition-normal); background: white;"
                            placeholder="••••••••"
                            onfocus="this.style.borderColor='var(--green-400)'; this.style.boxShadow='var(--green-shadow)'"
                            onblur="this.style.borderColor='var(--green-200)'; this.style.boxShadow='none'"
                        >
                    </div>

                    <!-- Remember Me -->
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: var(--gray-600);">
                            <input
                                type="checkbox"
                                name="remember"
                                style="width: 1rem; height: 1rem; accent-color: var(--green-600); border-radius: 3px;"
                            >
                            Beni hatırla
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="btn btn-primary"
                        style="width: 100%; padding: 0.875rem 1.5rem; font-size: 1rem; font-weight: 500; margin-top: 1.5rem;"
                    >
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Giriş Yap
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="user-card-actions" style="padding: 1.5rem; border-top: 1px solid var(--green-100); background: var(--green-50);">
                <div class="text-center" style="width: 100%;">
                    <p style="color: var(--gray-600); font-size: 0.9rem; margin-bottom: 1rem;">
                        Henüz hesabınız yok mu?
                    </p>
                    <a
                        href="{{ route('user.register') }}"
                        class="btn btn-secondary"
                        style="padding: 0.625rem 1.5rem; font-size: 0.95rem;"
                    >
                        <svg style="width: 1.125rem; height: 1.125rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Hesap Oluştur
                    </a>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center" style="margin-top: 1.5rem;">
            <a
                href="{{ route('user.index') }}"
                style="color: var(--green-600); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: var(--transition-normal);"
                onmouseover="this.style.color='var(--green-700)'"
                onmouseout="this.style.color='var(--green-600)'"
            >
                <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Ana Sayfaya Dön
            </a>
        </div>
    </div>
</div>
@endsection
