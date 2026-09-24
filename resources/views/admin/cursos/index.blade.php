@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1 text-dark-surface">
            <i class="bi bi-mortarboard-fill me-2 text-violet-primary"></i>Ikastaroak
        </h2>
        <p class="text-muted mb-0">Ikastaroen eskaintzaren kudeaketa administratiboa</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-corporate-outline btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Panela
        </a>
        <a href="{{ route('admin.cursos.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> + Ikastaro Berria
        </a>
    </div>
</div>

<div class="card card-corporate shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Ikastaroa bilatu..." data-table-filter="#cursos-table">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="cursos-table" class="table table-hover align-middle mb-0 table-custom">
                <thead>
                    <tr>
                        <th>Kodea</th>
                        <th>Izena</th>
                        <th>Orduak</th>
                        <th>Plazak (Libre/Guztira)</th>
                        <th>Prezioa</th>
                        <th>Egoera</th>
                        <th class="text-end">Ekintzak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cursos as $curso)
                        <tr>
                            <td><strong class="font-monospace text-violet-primary">{{ $curso->kodea }}</strong></td>
                            <td>
                                <strong class="text-dark-surface">{{ $curso->izena }}</strong>
                                <small class="d-block text-muted">{{ Str::limit($curso->deskribapena, 60) }}</small>
                            </td>
                            <td>{{ $curso->iraupena_orduak }}h</td>
                            <td>
                                <span class="badge {{ $curso->plazasDisponibles() > 0 ? 'badge-tech' : 'bg-danger' }}">
                                    {{ $curso->plazasDisponibles() }} / {{ $curso->plazak }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $curso->prezioa > 0 ? number_format($curso->prezioa, 2) . ' €' : 'Dohainik' }}</td>
                            <td>
                                <span class="badge {{ $curso->egoera === 'irekita' ? 'badge-green-deep' : ($curso->egoera === 'itxita' ? 'bg-danger' : 'badge-dark-surface') }}">
                                    {{ ucfirst($curso->egoera) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.cursos.show', $curso->id) }}" class="btn btn-sm btn-outline-primary" title="Xehetasunak">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-sm btn-primary" title="Editatu">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Ziur zaude \'{{ $curso->izena }}\' ikastaroa ezabatu nahi duzula?')" title="Ezabatu">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Ez dago ikastarorik erregistratuta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
