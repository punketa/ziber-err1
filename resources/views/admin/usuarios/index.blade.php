@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1 text-dark-surface">
            <i class="bi bi-people-fill me-2 text-dark-surface"></i>Erabiltzaileak
        </h2>
        <p class="text-muted mb-0">Sistemako erabiltzaile eta rolen kudeaketa administratiboa</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-corporate-outline btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Panela
        </a>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-cta btn-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Erabiltzaile Berria
        </a>
    </div>
</div>

<div class="card card-corporate shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Erabiltzailea bilatu..." data-table-filter="#usuarios-table">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="usuarios-table" class="table table-hover align-middle mb-0 table-custom">
                <thead>
                    <tr>
                        <th>Izena</th>
                        <th>Email</th>
                        <th>Rola</th>
                        <th>Matrikulak</th>
                        <th>Erregistro Data</th>
                        <th class="text-end">Ekintzak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $user)
                        <tr>
                            <td>
                                <strong class="text-dark-surface">{{ $user->name }}</strong>
                                @if($user->id === Auth::id())
                                    <span class="badge badge-violet-subtle ms-1">Zu</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->isAdmin() ? 'badge-violet' : 'badge-green-deep' }}">
                                    {{ $user->isAdmin() ? 'Administratzailea' : 'Ikaslea' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-violet-subtle border">
                                    {{ $user->matriculas_count }} ikastaro
                                </span>
                            </td>
                            <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-sm btn-primary" title="Editatu">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Ziur zaude '{{ $user->name }}' erabiltzailea ezabatu nahi duzula?" title="Ezabatu">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Ezin duzu zure erabiltzailea ezabatu">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Ez dago erabiltzailerik erregistratuta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
