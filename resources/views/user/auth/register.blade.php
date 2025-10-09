@extends('user.layout')

@section('title', __('user.create_account') . ' - DUT Vote')

@section('content')
<div class="min-h-screen flex items-center justify-center section-padding" style="padding-top: 2rem; padding-bottom: 2rem;">
    <div class="user-container" style="max-width: 480px;">
        <div class="user-card">
            <!-- Header -->
            <div class="user-card-header text-center">
                <h1 class="user-card-title" style="font-size: 1.75rem; margin-bottom: 0.5rem;">
                    {{ __('user.create_account') }}
                </h1>
                <p style="color: var(--gray-600); font-size: 0.95rem;">
                    {{ __('user.register_description') }}
                </p>
            </div>

            <!-- Register Form -->
            <div class="user-card-content">
                @if ($errors->any())
                    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                        <div style="font-weight: 500; margin-bottom: 0.5rem;">
                            <svg style="width: 1.25rem; height: 1.25rem; display: inline; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                            </svg>
                            {{ __('user.error') }}
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
                            <svg style="width: 1.25rem; height: 1.25rem; display: inline; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('user.register') }}" style="space-y: 1.5rem;">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" style="display: block; font-weight: 500; color: var(--gray-700); margin-bottom: 0.5rem;">
                            {{ __('user.name') }}
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--green-200); border-radius: var(--radius-md); font-size: 0.95rem; transition: var(--transition-normal); background: white;"
                            placeholder="Adınız ve soyadınız"
                            onfocus="this.style.borderColor='var(--green-400)'; this.style.boxShadow='var(--green-shadow)'"
                            onblur="this.style.borderColor='var(--green-200)'; this.style.boxShadow='none'"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" style="display: block; font-weight: 500; color: var(--gray-700); margin-bottom: 0.5rem;">
                            {{ __('user.email') }}
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
                            {{ __('user.password') }}
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--green-200); border-radius: var(--radius-md); font-size: 0.95rem; transition: var(--transition-normal); background: white;"
                            placeholder="{{ __('user.min_6_characters') }}"
                            onfocus="this.style.borderColor='var(--green-400)'; this.style.boxShadow='var(--green-shadow)'"
                            onblur="this.style.borderColor='var(--green-200)'; this.style.boxShadow='none'"
                        >
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" style="display: block; font-weight: 500; color: var(--gray-700); margin-bottom: 0.5rem;">
                            {{ __('user.password_confirmation') }}
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--green-200); border-radius: var(--radius-md); font-size: 0.95rem; transition: var(--transition-normal); background: white;"
                            placeholder="{{ __('user.password_confirmation_placeholder') }}"
                            onfocus="this.style.borderColor='var(--green-400)'; this.style.boxShadow='var(--green-shadow)'"
                            onblur="this.style.borderColor='var(--green-200)'; this.style.boxShadow='none'"
                        >
                    </div>

                    <!-- Terms Agreement -->
                    <div style="padding: 1rem; background: var(--green-50); border: 1px solid var(--green-200); border-radius: var(--radius-md);">
                        <label style="display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.875rem; color: var(--gray-600); cursor: pointer;">
                            <input
                                type="checkbox"
                                required
                                style="width: 1rem; height: 1rem; accent-color: var(--green-600); border-radius: 3px; margin-top: 0.125rem; flex-shrink: 0;"
                            >
                            <span>
                                {{ __('user.terms_agreement', ['terms' => '<a href="#" style="color: var(--green-600); text-decoration: underline;">' . __('user.terms_of_use') . '</a>', 'privacy' => '<a href="#" style="color: var(--green-600); text-decoration: underline;">' . __('user.privacy_policy') . '</a>']) }}
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="btn btn-primary"
                        style="width: 100%; padding: 0.875rem 1.5rem; font-size: 1rem; font-weight: 500; margin-top: 1.5rem;"
                    >
                        <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM3 20a6 6 0 0 1 12 0v1H3v-1Z"/>
                        </svg>
                        {{ __('user.create_account') }}
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="user-card-actions" style="padding: 1.5rem; border-top: 1px solid var(--green-100); background: var(--green-50);">
                <div class="text-center" style="width: 100%;">
                    <p style="color: var(--gray-600); font-size: 0.9rem; margin-bottom: 1rem;">
                        {{ __('user.already_have_account') }}
                    </p>
                    <a
                        href="{{ route('user.login') }}"
                        class="btn btn-secondary"
                        style="padding: 0.625rem 1.5rem; font-size: 0.95rem;"
                    >
                        <svg style="width: 1.125rem; height: 1.125rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/>
                        </svg>
                        {{ __('user.login') }}
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
                <svg style="width: 1rem; height: 1rem; display: inline; margin-right: 0.5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                </svg>
                {{ __('user.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
