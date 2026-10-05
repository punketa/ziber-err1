@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1 text-dark-surface">
            <i class="bi bi-mortarboard-fill me-2 text-violet-primary"></i>{{ $curso->izena }}
        </h2>
        <span class="badge badge-violet font-monospace">{{ $curso->kodea }}</span>
        <span class="badge {{ $curso->egoera === 'irekita' ? 'badge-green-deep' : 'badge-dark-surface' }} ms-1">{{ ucfirst($curso->egoera) }}</span>
    </div>
    <div>
        <a href="{{ route('admin.cursos.index') }}" class="btn btn-corporate-outline btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Zerrendara
        </a>
        <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Editatu
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-7">
        <div class="card card-corporate shadow-sm h-100">
            <div class="card-header card-header-clean py-3">
                <h5 class="mb-0 fw-bold text-dark-surface">Ikastaroaren Datuak</h5>
            </div>
            <div class="card-body bg-white">
                <p><strong>Deskribapena:</strong></p>
                <p class="text-secondary">{{ $curso->deskribapena }}</p>

                <div class="row g-2 mt-3 p-3 box-highlight">
                    <div class="col-6">
                        <small class="text-muted d-block">Iraupena:</small>
                        <p class="fw-semibold mb-2 text-dark-surface">{{ $curso->iraupena_orduak }} ordu</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Prezioa:</small>
                        <p class="fw-semibold mb-2 text-violet-primary">{{ $curso->prezioa > 0 ? number_format($curso->prezioa, 2) . ' €' : 'Dohainik' }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Hasiera data:</small>
                        <p class="fw-semibold mb-0 text-dark-surface">{{ $curso->hasiera_data ? $curso->hasiera_data->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Bukaera data:</small>
                        <p class="fw-semibold mb-0 text-dark-surface">{{ $curso->bukaera_data ? $curso->bukaera_data->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card card-corporate shadow-sm h-100">
            <div class="card-header card-header-clean py-3">
                <h5 class="mb-0 fw-bold text-dark-surface">Plazen Estatistikak</h5>
            </div>
            <div class="card-body text-center d-flex flex-column justify-content-center bg-white">
                <h1 class="display-4 fw-bold {{ $curso->plazasDisponibles() > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $curso->plazasDisponibles() }}
                </h1>
                <p class="text-muted">Plaza libre geratzen dira (Guztira: {{ $curso->plazak }})</p>
                <div class="progress progress-corporate mb-3">
                    @php
                        $ocupadas = $curso->plazak - $curso->plazasDisponibles();
                        $porcentaje = $curso->plazak > 0 ? min(100, round(($ocupadas / $curso->plazak) * 100)) : 0;
                    @endphp
                    <div class="progress-bar progress-bar-corporate" role="progressbar" style="width: {{ $porcentaje }}%;"></div>
                </div>
                <small class="text-muted">{{ $porcentaje }}% beteta</small>
            </div>
        </div>
    </div>
</div>

<div class="card card-corporate shadow-sm">
    <div class="card-header card-header-clean d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-dark-surface">Matrikulatutako Ikasleak</h5>
        <a href="{{ route('admin.matriculas.create') }}" class="btn btn-sm btn-primary">
            + Matrikulatu Ikaslea
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-custom">
                <thead>
                    <tr>
                        <th>Ikaslea</th>
                        <th>Email</th>
                        <th>Matrikula Data</th>
                        <th>Egoera</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($curso->matriculas as $m)
                        <tr>
                            <td><strong>{{ $m->usuario->name ?? 'Ezabatua' }}</strong></td>
                            <td>{{ $m->usuario->email ?? '-' }}</td>
                            <td>{{ $m->data ? $m->data->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="badge {{ $m->egoera === 'onartua' ? 'badge-green-deep' : ($m->egoera === 'pendiente' ? 'bg-warning text-dark' : 'badge-dark-surface') }}">
                                    {{ ucfirst($m->egoera) }}
                                </span>
                            </td>
                            <td>
                                <strong class="{{ $m->kalifikazioa !== null && $m->kalifikazioa >= 5 ? 'text-success' : 'text-danger' }}">
                                    {{ $m->kalifikazioa !== null ? number_format($m->kalifikazioa, 2) : 'Ebaluatu gabe' }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">Ez dago ikaslerik matrikulatuta ikastaro honetan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
