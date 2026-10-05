@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0 text-dark-surface">
                <i class="bi bi-card-plus me-2 text-green-deep"></i>Matrikula Berria Sortu
            </h2>
            <a href="{{ route('admin.matriculas.index') }}" class="btn btn-corporate-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Zerrendara
            </a>
        </div>

        <div class="card card-corporate shadow-sm">
            <div class="card-body p-4 bg-white">
                <form action="{{ route('admin.matriculas.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="usuario_id" class="form-label fw-semibold text-dark-surface">Ikaslea:</label>
                        <select name="usuario_id" id="usuario_id" class="form-select form-select-corporate @error('usuario_id') is-invalid @enderror" required>
                            <option value="">-- Hautatu ikaslea --</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" {{ old('usuario_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('usuario_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="curso_id" class="form-label fw-semibold text-dark-surface">Ikastaroa:</label>
                        <select name="curso_id" id="curso_id" class="form-select form-select-corporate @error('curso_id') is-invalid @enderror" required>
                            <option value="">-- Hautatu ikastaroa --</option>
                            @foreach($cursos as $c)
                                <option value="{{ $c->id }}" {{ old('curso_id') == $c->id ? 'selected' : '' }}>
                                    [{{ $c->kodea }}] {{ $c->izena }} ({{ $c->plazasDisponibles() }} libre)
                                </option>
                            @endforeach
                        </select>
                        @error('curso_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label for="data" class="form-label fw-semibold text-dark-surface">Matrikula Data:</label>
                            <input type="date" name="data" id="data" class="form-control form-control-corporate @error('data') is-invalid @enderror" value="{{ old('data', date('Y-m-d')) }}" required>
                            @error('data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="egoera" class="form-label fw-semibold text-dark-surface">Egoera:</label>
                            <select name="egoera" id="egoera" class="form-select form-select-corporate @error('egoera') is-invalid @enderror" required>
                                <option value="onartua" {{ old('egoera') === 'onartua' ? 'selected' : '' }}>Onartua</option>
                                <option value="pendiente" {{ old('egoera') === 'pendiente' ? 'selected' : '' }}>Zain</option>
                                <option value="baja" {{ old('egoera') === 'baja' ? 'selected' : '' }}>Baja</option>
                            </select>
                            @error('egoera')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="kalifikazioa" class="form-label fw-semibold text-dark-surface">Kalifikazioa (0.00 - 10.00, hautazkoa):</label>
                        <input type="number" step="0.01" min="0" max="10" name="kalifikazioa" id="kalifikazioa" class="form-control form-control-corporate @error('kalifikazioa') is-invalid @enderror" value="{{ old('kalifikazioa') }}" placeholder="8.50">
                        @error('kalifikazioa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.matriculas.index') }}" class="btn btn-corporate-outline">Utzi</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Gorde Matrikula
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
