@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0 text-dark-surface">
                <i class="bi bi-pencil-square me-2 text-violet-primary"></i>Ikastaroa Editatu
            </h2>
            <a href="{{ route('admin.cursos.index') }}" class="btn btn-corporate-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Itzuli zerrendara
            </a>
        </div>

        <div class="card card-corporate shadow-sm">
            <div class="card-body p-4 bg-white">
                <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="kodea" class="form-label fw-semibold text-dark-surface">Kodea:</label>
                            <input type="text" name="kodea" id="kodea" class="form-control form-control-corporate @error('kodea') is-invalid @enderror" value="{{ old('kodea', $curso->kodea) }}" required>
                            @error('kodea')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label for="izena" class="form-label fw-semibold text-dark-surface">Izena:</label>
                            <input type="text" name="izena" id="izena" class="form-control form-control-corporate @error('izena') is-invalid @enderror" value="{{ old('izena', $curso->izena) }}" required>
                            @error('izena')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskribapena" class="form-label fw-semibold text-dark-surface">Deskribapena:</label>
                        <textarea name="deskribapena" id="deskribapena" rows="3" class="form-control form-control-corporate @error('deskribapena') is-invalid @enderror" required>{{ old('deskribapena', $curso->deskribapena) }}</textarea>
                        @error('deskribapena')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="iraupena_orduak" class="form-label fw-semibold text-dark-surface">Iraupena (Orduak):</label>
                            <input type="number" name="iraupena_orduak" id="iraupena_orduak" class="form-control form-control-corporate @error('iraupena_orduak') is-invalid @enderror" value="{{ old('iraupena_orduak', $curso->iraupena_orduak) }}" min="1" required>
                            @error('iraupena_orduak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="plazak" class="form-label fw-semibold text-dark-surface">Plazak guztira:</label>
                            <input type="number" name="plazak" id="plazak" class="form-control form-control-corporate @error('plazak') is-invalid @enderror" value="{{ old('plazak', $curso->plazak) }}" min="1" required>
                            @error('plazak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="prezioa" class="form-label fw-semibold text-dark-surface">Prezioa (€):</label>
                            <input type="number" step="0.01" name="prezioa" id="prezioa" class="form-control form-control-corporate @error('prezioa') is-invalid @enderror" value="{{ old('prezioa', $curso->prezioa) }}" min="0" required>
                            @error('prezioa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="hasiera_data" class="form-label fw-semibold text-dark-surface">Hasiera Data:</label>
                            <input type="date" name="hasiera_data" id="hasiera_data" class="form-control form-control-corporate @error('hasiera_data') is-invalid @enderror" value="{{ old('hasiera_data', $curso->hasiera_data ? $curso->hasiera_data->format('Y-m-d') : '') }}" required>
                            @error('hasiera_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bukaera_data" class="form-label fw-semibold text-dark-surface">Bukaera Data:</label>
                            <input type="date" name="bukaera_data" id="bukaera_data" class="form-control form-control-corporate @error('bukaera_data') is-invalid @enderror" value="{{ old('bukaera_data', $curso->bukaera_data ? $curso->bukaera_data->format('Y-m-d') : '') }}" required>
                            @error('bukaera_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="egoera" class="form-label fw-semibold text-dark-surface">Egoera:</label>
                            <select name="egoera" id="egoera" class="form-select form-select-corporate @error('egoera') is-invalid @enderror" required>
                                <option value="irekita" {{ old('egoera', $curso->egoera) === 'irekita' ? 'selected' : '' }}>Irekita</option>
                                <option value="itxita" {{ old('egoera', $curso->egoera) === 'itxita' ? 'selected' : '' }}>Itxita</option>
                                <option value="amaituta" {{ old('egoera', $curso->egoera) === 'amaituta' ? 'selected' : '' }}>Amaituta</option>
                            </select>
                            @error('egoera')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.cursos.index') }}" class="btn btn-corporate-outline">Utzi</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-arrow-repeat me-1"></i> Eguneratu Ikastaroa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
