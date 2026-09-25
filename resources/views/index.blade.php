@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4 pb-2 border-corporate-bottom">
    <div class="col-md-8">
        <h1 class="h2 fw-bold mb-1 text-dark-surface">
            <i class="bi bi-mortarboard-fill me-2 text-violet-primary"></i>Ikastaroen Eskaintza
        </h1>
        <p class="text-muted mb-0">Ikastaroen eskaintzaren kudeaketa administratiboa</p>
    </div>
    <div class="col-md-4 mt-3 mt-md-0">
        <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control form-control-corporate" placeholder="Bilatu ikastaroa...">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
            @if(!empty($search))
                <a href="{{ route('home') }}" class="btn btn-outline-secondary" title="Garbitu bilaketa">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </form>
    </div>
</div>

@guest
    <div class="card box-highlight shadow-sm mb-4">
        <div class="card-body p-4 text-center">
            <h4 class="fw-bold text-dark-surface">Ikastaro batean parte hartu nahi duzu?</h4>
            <p class="text-muted mb-3">Sortu zure kontua edo hasi saioa plataforman dauden ikastaroetan matrikulatu ahal izateko.</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary px-4">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Hasi Saioa
                </a>
                <a href="{{ route('register') }}" class="btn btn-cta px-4">
                    <i class="bi bi-person-plus me-1"></i> Erregistratu
                </a>
            </div>
        </div>
    </div>
@endguest

<div class="row g-4">
    @forelse($cursos as $curso)
        @php
            $disponibles = $curso->plazasDisponibles();
            $isEnrolled = in_array($curso->id, $userMatriculasIds ?? []);
        @endphp
        <div class="col-lg-6">
            <div class="card h-100 card-corporate shadow-sm">
                <div class="card-header card-header-clean d-flex justify-content-between align-items-center py-3">
                    <div>
                        <span class="badge badge-violet text-uppercase fw-bold">{{ $curso->kodea }}</span>
                        <span class="badge {{ $curso->egoera === 'irekita' ? 'badge-green-deep' : 'badge-dark-surface' }} ms-1">
                            {{ ucfirst($curso->egoera) }}
                        </span>
                    </div>
                    <span class="badge badge-violet-subtle">
                        <i class="bi bi-clock me-1"></i> {{ $curso->iraupena_orduak }} ordu
                    </span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-dark-surface">{{ $curso->izena }}</h5>
                    <p class="card-text text-secondary flex-grow-1">{{ $curso->deskribapena }}</p>

                    <div class="p-3 box-highlight mb-3">
                        <div class="row text-center">
                            <div class="col-4 border-end border-lavender">
                                <small class="text-muted d-block">Hasiera</small>
                                <strong class="text-dark-surface">{{ $curso->hasiera_data ? $curso->hasiera_data->format('d/m/Y') : '-' }}</strong>
                            </div>
                            <div class="col-4 border-end border-lavender">
                                <small class="text-muted d-block">Bukaera</small>
                                <strong class="text-dark-surface">{{ $curso->bukaera_data ? $curso->bukaera_data->format('d/m/Y') : '-' }}</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Plaza libreak</small>
                                <strong class="{{ $disponibles > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $disponibles }} / {{ $curso->plazak }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-corporate-top">
                        <span class="fs-5 fw-bold text-dark-surface">
                            {{ $curso->prezioa > 0 ? number_format($curso->prezioa, 2) . ' €' : 'DOHAINIK' }}
                        </span>

                        <div>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-lock me-1"></i> Matrikulatu
                                </a>
                            @else
                                @if(Auth::user()->isIkasle())
                                    @if($isEnrolled)
                                        <span class="badge badge-green-deep py-2 px-3">
                                            <i class="bi bi-check2-circle me-1"></i> Dagoeneko matrikulatuta
                                        </span>
                                    @elseif($curso->isIrekita())
                                        <form action="{{ route('cursos.enroll', $curso->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-cta btn-sm" data-confirm="'{{ $curso->izena }}' ikastaroan matrikulatu nahi duzu?">
                                                <i class="bi bi-plus-circle me-1"></i> Matrikulatu
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="bi bi-x-circle me-1"></i> Ez dago plazarik
                                        </button>
                                    @endif
                                @elseif(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil me-1"></i> Editatu
                                    </a>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert text-center py-5 box-highlight text-dark-surface">
                <i class="bi bi-info-circle fs-2 d-block mb-2 text-violet-primary"></i>
                <h5>Ez da ikastarorik aurkitu</h5>
                <p class="mb-0 text-muted">Ez dago une honetan bilaketarekin bat datorren ikastarorik erabilgarri.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
