@extends('layouts.app')

@push('filament-styles')
    {{-- Filament styles could be pushed here if available --}}
@endpush

@section('content')
    <section class="hero">
        <div class="container container-small text-center">
            <span class="badge-soft">Demo</span>
            <h1 class="title mt-3">Hoşgeldiniz — Proje Paneli</h1>
            <p class="subtitle">Filament görsellerinden ilham alan, admin olmayan kullanıcılar için hazırlanmış demo açılış sayfası.</p>

            <div class="cta">
                <a class="btn btn-primary btn-lg me-2" href="/register">Ücretsiz Başla</a>
                <a class="btn btn-outline-primary btn-lg" href="/login">Giriş Yap</a>
            </div>

            <div class="mt-4 card-ghost d-inline-block text-start" style="max-width:880px; width:100%">
                <div class="row g-0">
                    <div class="col-md-8 p-4">
                        <h4>Hemen deneyin</h4>
                        <p class="muted">Kullanıcı arayüzü bileşenleri ve temel CTA'lar içerir. Bu alanı özelleştirerek kendi içeriklerinizi ekleyin.</p>
                    </div>
                    <div class="col-md-4 p-4 d-flex align-items-center">
                        <img src="/images/demo-illustration.png" alt="demo" style="width:100%;max-width:140px;opacity:0.95">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="container container-small mt-5">
        <h3 class="mb-3">Özellikler</h3>
        <div class="features">
            <div class="feature">
                <h4>Kolay Yönetim</h4>
                <p class="muted">Kullanıcı ve içerik yönetimi için temiz arayüz.</p>
            </div>
            <div class="feature">
                <h4>Hızlı Entegrasyon</h4>
                <p class="muted">Hazır bileşenler ile hızlı kurulum.</p>
            </div>
            <div class="feature">
                <h4>Güvenli</h4>
                <p class="muted">Rol ve izinler ile gelişmiş erişim kontrolü.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container container-small d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <strong>Proje Paneli</strong>
                <div class="muted">© 2025 Visio Soft</div>
            </div>

            <div class="mt-3 mt-md-0">
                <a class="me-3 text-decoration-none" href="#">Yardım</a>
                <a class="me-3 text-decoration-none" href="#contact">İletişim</a>
                <a class="text-decoration-none" href="#">Gizlilik</a>
            </div>
        </div>
    </footer>

@endsection

@push('filament-scripts')
    {{-- Filament scripts can be pushed here if needed --}}
@endpush
