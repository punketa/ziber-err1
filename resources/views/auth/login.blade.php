@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-corporate shadow-sm">
            <div class="card-header card-header-dark text-white text-center py-3">
                <h4 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-right me-2 text-green-tech"></i>Hasi Saioa</h4>
            </div>
            <div class="card-body p-4 bg-white">
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark-surface">Posta elektronikoa:</label>
                        <input type="email" name="email" id="email" class="form-control form-control-corporate @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="adibidea@domeinua.eus">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-dark-surface">Pasahitza:</label>
                        <input type="password" name="password" id="password" class="form-control form-control-corporate @error('password') is-invalid @enderror" required placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label small text-muted">Gogoratu nire saioa</label>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-corporate-primary fw-semibold py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sartu
                        </button>
                    </div>

                    <div class="text-center">
                        <span class="text-muted small">Ez duzu konturik?</span>
                        <a href="{{ route('register') }}" class="small fw-semibold text-decoration-none text-violet-primary ms-1">Erregistratu hemen</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
