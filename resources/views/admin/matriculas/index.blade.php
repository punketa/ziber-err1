@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1 text-dark-surface">
            <i class="bi bi-card-checklist me-2 text-green-deep"></i>Matrikulak
        </h2>
        <p class="text-muted mb-0">Ikasleen matrikulen, egoeren eta kalifikazioen kudeaketa</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-corporate-outline btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Panela
        </a>
        <a href="{{ route('admin.matriculas.create') }}" class="btn btn-tech btn-sm">
            <i class="bi bi-plus-lg me-1"></i> + Matrikula Berria
        </a>
    </div>
</div>

<div class="card card-corporate shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Matrikula bilatu..." data-table-filter="#matriculas-table">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="matriculas-table" class="table table-hover align-middle mb-0 table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ikaslea</th>
                        <th>Ikastaroa</th>
                        <th>Matrikula Data</th>
                        <th>Egoera</th>
                        <th>Kalifikazioa</th>
                        <th class="text-end">Ekintzak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matriculas as $m)
                        <tr>
                            <td>{{ $m->id }}</td>
                            <td>
                                <strong class="text-dark-surface">{{ $m->usuario->name ?? 'Ezabatua' }}</strong>
                                <small class="d-block text-muted">{{ $m->usuario->email ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge badge-violet font-monospace">{{ $m->curso->kodea ?? 'N/A' }}</span>
                                <span class="fw-semibold ms-1 text-dark-surface">{{ $m->curso->izena ?? 'Ikastaro ezabatua' }}</span>
                            </td>
                            <td>{{ $m->data ? $m->data->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="badge {{ $m->egoera === 'onartua' ? 'badge-green-deep' : ($m->egoera === 'pendiente' ? 'bg-warning text-dark' : 'badge-dark-surface') }}">
                                    {{ ucfirst($m->egoera) }}
                                </span>
                            </td>
                            <td>
                                @if($m->kalifikazioa !== null)
                                    <strong class="fs-6 {{ $m->kalifikazioa >= 5 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($m->kalifikazioa, 2) }}
                                    </strong>
                                @else
                                    <span class="text-muted small">Ezarri gabe</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.matriculas.edit', $m->id) }}" class="btn btn-sm btn-primary" title="Editatu">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.matriculas.destroy', $m->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ziur zaude matrikula hau ezabatu nahi duzula?')" title="Ezabatu">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Ez dago matrikularik erregistratuta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
