@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-corporate-bottom">
    <div>
        <h1 class="h2 fw-bold mb-1 text-dark-surface">
            <i class="bi bi-journal-bookmark-fill me-2 text-violet-primary"></i>Nire Matrikulak
        </h1>
        <p class="text-muted mb-0">Zure matrikulazio aktiboen egoera eta kalifikazioak</p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Ikastaro gehiagotan matrikulatu
    </a>
</div>

<div class="card card-corporate shadow-sm">
    <div class="card-header card-header-clean py-3">
        <h5 class="mb-0 fw-bold text-dark-surface">Matrikulatutako Ikastaroen Zerrenda</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-custom">
                <thead>
                    <tr>
                        <th>Kodea</th>
                        <th>Ikastaroa</th>
                        <th>Datak</th>
                        <th>Matrikula Data</th>
                        <th>Egoera</th>
                        <th>Kalifikazioa</th>
                        <th class="text-end">Ekintzak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matriculas as $m)
                        <tr>
                            <td>
                                <span class="badge badge-violet font-monospace">{{ $m->curso->kodea ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <strong class="text-dark-surface">{{ $m->curso->izena ?? 'Ikastaro ezabatua' }}</strong>
                                <small class="d-block text-muted">{{ Str::limit($m->curso->deskribapena ?? '', 70) }}</small>
                            </td>
                            <td class="small">
                                {{ $m->curso->hasiera_data ? $m->curso->hasiera_data->format('d/m/Y') : '-' }} &bull;
                                {{ $m->curso->bukaera_data ? $m->curso->bukaera_data->format('d/m/Y') : '-' }}
                            </td>
                            <td>{{ $m->data ? $m->data->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="badge {{ $m->egoera === 'onartua' ? 'badge-green-deep' : ($m->egoera === 'pendiente' ? 'bg-warning text-dark' : 'badge-dark-surface') }}">
                                    {{ ucfirst($m->egoera) }}
                                </span>
                            </td>
                            <td>
                                @if($m->kalifikazioa !== null)
                                    <span class="fw-bold fs-6 {{ $m->kalifikazioa >= 5 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($m->kalifikazioa, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted small">Ebaluatu gabe</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($m->egoera !== 'baja')
                                    <form action="{{ route('student.matriculas.cancel', $m->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="Ziur zaude matrikula honi baja eman nahi diozula?">
                                            <i class="bi bi-x-circle me-1"></i> Baja eman
                                        </button>
                                    </form>
                                @else
                                    <span class="badge badge-violet-subtle border">Baja emanda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open fs-2 d-block mb-2 text-violet-secondary"></i>
                                <h6>Ez daukazu matrikularik une honetan</h6>
                                <p class="mb-3 small">Begiratu gure eskaintza eta eman izena nahi duzun ikastaroan.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                                    Ikastaroak Ikusi
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
