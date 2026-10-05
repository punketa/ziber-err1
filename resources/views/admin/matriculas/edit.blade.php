@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0 text-dark-surface">
                <i class="bi bi-pencil-square me-2 text-violet-primary"></i>Matrikula Editatu
            </h2>
            <a href="{{ route('admin.matriculas.index') }}" class="btn btn-corporate-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Zerrendara
            </a>
        </div>

        <div class="card card-corporate shadow-sm">
            <div class="card-body p-4 bg-white">
                <div class="p-3 box-highlight mb-3">
                    <p class="mb-1 text-dark-surface"><strong>Ikaslea:</strong> {{ $matricula->usuario->name ?? 'Ezabatua' }} ({{ $matricula->usuario->email ?? '' }})</p>
                    <p class="mb-0 text-dark-surface"><strong>Ikastaroa:</strong> [{{ $matricula->curso->kodea ?? '' }}] {{ $matricula->curso->izena ?? 'Ezabatua' }}</p>
                </div>

                <form action="{{ route('admin.matriculas.update', $matricula->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label for="data" class="form-label fw-semibold text-dark-surface">Matrikula Data:</label>
                            <input type="date" name="data" id="data" class="form-control form-control-corporate @error('data') is-invalid @enderror" value="{{ old('data', $matricula->data ? $matricula->data->format('Y-m-d') : '') }}" required>
                            @error('data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="egoera" class="form-label fw-semibold text-dark-surface">Egoera:</label>
                            <select name="egoera" id="egoera" class="form-select form-select-corporate @error('egoera') is-invalid @enderror" required>
                                <option value="onartua" {{ old('egoera', $matricula->egoera) === 'onartua' ? 'selected' : '' }}>Onartua</option>
                                <option value="pendiente" {{ old('egoera', $matricula->egoera) === 'pendiente' ? 'selected' : '' }}>Zain</option>
                                <option value="baja" {{ old('egoera', $matricula->egoera) === 'baja' ? 'selected' : '' }}>Baja</option>
                            </select>
                            @error('egoera')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="kalifikazioa" class="form-label fw-semibold text-dark-surface">Kalifikazioa (0.00 - 10.00):</label>
                        <input type="number" step="0.01" min="0" max="10" name="kalifikazioa" id="kalifikazioa" class="form-control form-control-corporate @error('kalifikazioa') is-invalid @enderror" value="{{ old('kalifikazioa', $matricula->kalifikazioa) }}" placeholder="Hutsik ebaluatu gabe badago">
                        @error('kalifikazioa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.matriculas.index') }}" class="btn btn-corporate-outline">Utzi</a>
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
