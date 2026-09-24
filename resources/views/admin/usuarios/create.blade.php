@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0 text-dark-surface">
                <i class="bi bi-person-plus-fill me-2 text-dark-surface"></i>Erabiltzaile Berria Sortu
            </h2>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-corporate-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Zerrendara
            </a>
        </div>

        <div class="card card-corporate shadow-sm">
            <div class="card-body p-4 bg-white">
                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark-surface">Izen-abizenak:</label>
                        <input type="text" name="name" id="name" class="form-control form-control-corporate @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Izen Abizena">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark-surface">Email helbidea:</label>
                        <input type="email" name="email" id="email" class="form-control form-control-corporate @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="erabiltzailea@domeinua.eus">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-dark-surface">Pasahitza:</label>
                        <input type="password" name="password" id="password" class="form-control form-control-corporate @error('password') is-invalid @enderror" required placeholder="Gutxienez 8 karaktere">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label fw-semibold text-dark-surface">Rola:</label>
                        <select name="role" id="role" class="form-select form-select-corporate @error('role') is-invalid @enderror" required>
                            <option value="ikasle" {{ old('role') === 'ikasle' ? 'selected' : '' }}>Ikaslea</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administratzailea</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-corporate-outline">Utzi</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Gorde Erabiltzailea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
