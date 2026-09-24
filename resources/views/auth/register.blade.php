@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card card-corporate shadow-sm">
            <div class="card-header card-header-dark text-white text-center py-3">
                <h4 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2 text-green-tech"></i>Erregistroa</h4>
            </div>
            <div class="card-body p-4 bg-white">
                <form action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark-surface">Izen-abizenak:</label>
                        <input type="text" name="name" id="name" class="form-control form-control-corporate @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus placeholder="Izen-abizenak">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark-surface">Posta elektronikoa:</label>
                        <input type="email" name="email" id="email" class="form-control form-control-corporate @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="erabiltzailea@domeinua.eus">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold text-dark-surface">Pasahitza:</label>
                            <input type="password" name="password" id="password" class="form-control form-control-corporate @error('password') is-invalid @enderror" required placeholder="Gutxienez 8 karaktere">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark-surface">Berretsi pasahitza:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-corporate" required placeholder="Pasahitza errepikatu">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-cta py-2">
                            <i class="bi bi-person-check-fill me-1"></i> Kontua Sortu
                        </button>
                    </div>

                    <div class="text-center">
                        <span class="text-muted small">Dagoeneko erregistratuta zaude?</span>
                        <a href="{{ route('login') }}" class="small fw-semibold text-decoration-none text-violet-primary ms-1">Hasi saioa hemen</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
