@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0 text-dark-surface">
                <i class="bi bi-pencil-square me-2 text-violet-primary"></i>Erabiltzailea Editatu
            </h2>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-corporate-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Zerrendara
            </a>
        </div>

        <div class="card card-corporate shadow-sm">
            <div class="card-body p-4 bg-white">
                <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark-surface">Izen-abizenak:</label>
                        <input type="text" name="name" id="name" class="form-control form-control-corporate @error('name') is-invalid @enderror" value="{{ old('name', $usuario->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark-surface">Email helbidea:</label>
                        <input type="email" name="email" id="email" class="form-control form-control-corporate @error('email') is-invalid @enderror" value="{{ old('email', $usuario->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-dark-surface">Pasahitz berria (aldatu nahi ez bada, utzi hutsik):</label>
                        <input type="password" name="password" id="password" class="form-control form-control-corporate @error('password') is-invalid @enderror" placeholder="••••••••">
                        <small class="text-muted">Gutxienez 8 karaktere aldatu nahi bada.</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label fw-semibold text-dark-surface">Rola:</label>
                        <select name="role" id="role" class="form-select form-select-corporate @error('role') is-invalid @enderror" required>
                            <option value="ikasle" {{ old('role', $usuario->role) === 'ikasle' ? 'selected' : '' }}>Ikaslea</option>
                            <option value="admin" {{ old('role', $usuario->role) === 'admin' ? 'selected' : '' }}>Administratzailea</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-corporate-outline">Utzi</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-arrow-repeat me-1"></i> Aldaketak Gorde
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
