@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('home') }}" class="btn btn-corporate-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Ikastaro guztietara itzuli
            </a>
            <span class="badge {{ $curso->egoera === 'irekita' ? 'badge-green-deep' : 'badge-dark-surface' }} fs-6">
                {{ ucfirst($curso->egoera) }}
            </span>
        </div>

        <div class="card card-corporate shadow-sm">
            <div class="card-header card-header-clean py-3">
                <span class="badge badge-violet font-monospace">{{ $curso->kodea }}</span>
                <h2 class="card-title fw-bold mt-2 mb-0 text-dark-surface">{{ $curso->izena }}</h2>
            </div>
            <div class="card-body p-4 bg-white">
                <h5 class="fw-semibold text-dark-surface">Ikastaroaren Deskribapena</h5>
                <p class="text-secondary leading-relaxed mb-4">{{ $curso->deskribapena }}</p>

                <div class="row g-3 p-3 box-highlight mb-4 text-center">
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Iraupena</small>
                        <strong class="fs-6 text-dark-surface">{{ $curso->iraupena_orduak }} ordu</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Hasiera - Bukaera</small>
                        <strong class="fs-6 text-dark-surface">{{ $curso->hasiera_data ? $curso->hasiera_data->format('d/m/Y') : '-' }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Plaza Libreak</small>
                        <strong class="fs-6 {{ $curso->plazasDisponibles() > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $curso->plazasDisponibles() }} / {{ $curso->plazak }}
                        </strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Prezioa</small>
                        <strong class="fs-6 text-violet-primary">{{ $curso->prezioa > 0 ? number_format($curso->prezioa, 2) . ' €' : 'DOHAINIK' }}</strong>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-corporate-top">
                    <span class="text-muted small">
                        <i class="bi bi-shield-check me-1 text-green-tech"></i> Ziurtagiri ofiziala ikastaroa gainditzean
                    </span>

                    <div>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Hasi Saioa Matrikulatzeko
                            </a>
                        @else
                            @if(Auth::user()->isIkasle())
                                @if($isEnrolled)
                                    <span class="badge badge-green-deep py-2 px-3 fs-6">
                                        <i class="bi bi-check2-circle me-1"></i> Dagoeneko matrikulatuta zaude
                                    </span>
                                @elseif($curso->isIrekita())
                                    <form action="{{ route('cursos.enroll', $curso->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-cta px-4" onclick="return confirm('\'{{ $curso->izena }}\' ikastaroan matrikulatu nahi duzu?')">
                                            <i class="bi bi-plus-circle me-1"></i> Matrikulatu Orain
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-x-circle me-1"></i> Ez dago plazarik
                                    </button>
                                @endif
                            @elseif(Auth::user()->isAdmin())
                                <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-1"></i> Editatu
                                </a>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
