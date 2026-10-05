@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-corporate-bottom">
    <div>
        <h1 class="h2 fw-bold mb-1 text-dark-surface">
            <i class="bi bi-shield-lock-fill me-2 text-violet-primary"></i>Administrazio Panela
        </h1>
        <p class="text-muted mb-0">Ikastaroen, erabiltzaileen eta matrikulen kudeaketa orokorra</p>
    </div>
    <div>
        <span class="badge badge-violet p-2 fs-6">
            <i class="bi bi-person-badge me-1"></i> Admin modua
        </span>
    </div>
</div>

<!-- Estadísticas Generales con Paleta Corporativa -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card-total shadow-sm">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase mb-1">Ikastaroak Guztira</h6>
                    <h2 class="fw-bold mb-0 text-white">{{ $stats['total_cursos'] }}</h2>
                    <small class="text-lavender">{{ $stats['cursos_irekita'] }} irekita matrikulaziorako</small>
                </div>
                <i class="bi bi-mortarboard fs-1 text-white-50"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('admin.cursos.index') }}" class="text-white text-decoration-none small fw-semibold">
                    Kudeatu Ikastaroak <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card-tech shadow-sm">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase mb-1">Erabiltzaileak Guztira</h6>
                    <h2 class="fw-bold mb-0 text-white">{{ $stats['total_usuarios'] }}</h2>
                    <small class="text-lavender">{{ $stats['total_alumnos'] }} ikasle &bull; {{ $stats['total_admins'] }} admin</small>
                </div>
                <i class="bi bi-people fs-1 text-white-50"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('admin.usuarios.index') }}" class="text-white text-decoration-none small fw-semibold">
                    Kudeatu Erabiltzaileak <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card-success shadow-sm">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase mb-1">Matrikulak</h6>
                    <h2 class="fw-bold mb-0 text-white">{{ $stats['total_matriculas'] }}</h2>
                    <small class="text-lavender">{{ $stats['matriculas_activas'] }} onartuta eta aktibo</small>
                </div>
                <i class="bi bi-card-checklist fs-1 text-white-50"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('admin.matriculas.index') }}" class="text-white text-decoration-none small fw-semibold">
                    Kudeatu Matrikulak <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Accesos directos a los CRUDs -->
<div class="card card-corporate mb-4 shadow-sm">
    <div class="card-header card-header-clean py-3">
        <h5 class="mb-0 fw-bold text-dark-surface">
            <i class="bi bi-grid-3x3-gap-fill me-2 text-violet-primary"></i>Kudeaketa Zentroa
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="box-highlight p-3 text-center">
                    <i class="bi bi-mortarboard fs-2 d-block mb-2 text-violet-primary"></i>
                    <h6 class="fw-bold text-dark-surface">Ikastaroak</h6>
                    <p class="small text-muted mb-3">Ikastaroen eskaintza, orduak, datak eta plazak kudeatu.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.cursos.index') }}" class="btn btn-outline-primary btn-sm">Zerrenda</a>
                        <a href="{{ route('admin.cursos.create') }}" class="btn btn-primary btn-sm">+ Berria</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box-highlight p-3 text-center">
                    <i class="bi bi-people fs-2 d-block mb-2 text-dark-surface"></i>
                    <h6 class="fw-bold text-dark-surface">Erabiltzaileak</h6>
                    <p class="small text-muted mb-3">Ikasle eta administratzaileak kudeatu, rolak aldatu.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-corporate-outline btn-sm">Zerrenda</a>
                        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-sm btn-secondary">+ Berria</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box-highlight p-3 text-center">
                    <i class="bi bi-card-checklist fs-2 d-block mb-2 text-green-deep"></i>
                    <h6 class="fw-bold text-dark-surface">Matrikulak</h6>
                    <p class="small text-muted mb-3">Ikasleak ikastaroetan matrikulatu, notak eta egoera jarri.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.matriculas.index') }}" class="btn btn-corporate-outline btn-sm">Zerrenda</a>
                        <a href="{{ route('admin.matriculas.create') }}" class="btn btn-sm btn-tech">+ Berria</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tablas de Actividad Reciente -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-corporate shadow-sm h-100">
            <div class="card-header card-header-clean d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-bold mb-0 text-dark-surface"><i class="bi bi-clock-history me-2 text-violet-secondary"></i>Azken Matrikulazioak</h6>
                <a href="{{ route('admin.matriculas.index') }}" class="btn btn-sm btn-corporate-outline">Guztiak Ikusi</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-custom">
                        <thead>
                            <tr>
                                <th>Ikaslea</th>
                                <th>Ikastaroa</th>
                                <th>Data</th>
                                <th>Egoera</th>
                                <th>Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($azken_matrikulak as $m)
                                <tr>
                                    <td>
                                        <strong>{{ $m->usuario->name ?? 'Ezabatua' }}</strong><br>
                                        <small class="text-muted">{{ $m->usuario->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-violet-subtle font-monospace">{{ $m->curso->kodea ?? '' }}</span>
                                        <small class="d-block">{{ $m->curso->izena ?? 'Ezabatua' }}</small>
                                    </td>
                                    <td>{{ $m->data ? $m->data->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <span class="badge {{ $m->egoera === 'onartua' ? 'badge-green-deep' : ($m->egoera === 'pendiente' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ ucfirst($m->egoera) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $m->kalifikazioa !== null ? ($m->kalifikazioa >= 5 ? 'text-success' : 'text-danger') : 'text-muted' }}">
                                            {{ $m->kalifikazioa !== null ? number_format($m->kalifikazioa, 2) : 'Ezarri gabe' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Ez dago matrikularik oraindik.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-corporate shadow-sm h-100">
            <div class="card-header card-header-clean d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-bold mb-0 text-dark-surface"><i class="bi bi-mortarboard me-2 text-violet-secondary"></i>Azken Ikastaroak</h6>
                <a href="{{ route('admin.cursos.index') }}" class="btn btn-sm btn-corporate-outline">Guztiak Ikusi</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($azken_ikastaroak as $c)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="badge badge-violet mb-1">{{ $c->kodea }}</span>
                                <h6 class="mb-0 fw-semibold text-dark-surface">{{ $c->izena }}</h6>
                                <small class="text-muted">{{ $c->hasiera_data ? $c->hasiera_data->format('d/m/Y') : '' }} - {{ $c->bukaera_data ? $c->bukaera_data->format('d/m/Y') : '' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $c->egoera === 'irekita' ? 'badge-green-deep' : 'badge-dark-surface' }}">
                                    {{ ucfirst($c->egoera) }}
                                </span>
                                <small class="d-block text-muted mt-1">{{ $c->plazasDisponibles() }} libre</small>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-3">Ez dago ikastarorik.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
