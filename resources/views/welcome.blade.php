@extends('layouts.main')

@section('title', 'IF Eventos')

@section('content')

{{-- Seção do Banner de Busca --}}
<div class="search-banner text-center text-white d-flex align-items-center justify-content-center">
    <div>
        <h1 class="display-4 fw-bold mb-3">Busque um Evento</h1>
        <form action="/" method="GET">
            <input type="text" id="search" name="search" class="form-control form-control-lg" placeholder="Procurar por nome, cidade, categoria...">
        </form>
    </div>
</div>

{{-- Seção dos Eventos --}}
<div class="container my-5">

    {{-- Título da Seção --}}
    <div class="row mb-4">
        <div class="col-12">
            @if ($search)
                <h2>Buscando por: <span class="text-primary">{{ $search }}</span></h2>
            @else
                <h2>Próximos Eventos</h2>
                <p class="lead text-muted">Veja os eventos que acontecerão nos próximos dias.</p>
            @endif
        </div>
    </div>

    {{-- Grid de Cards de Eventos --}}
    <div class="row">
        @forelse ($events as $event)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card event-card h-100 shadow-sm border-0">
                    <img src="/img/events/{{ $event->image }}" class="card-img-top" alt="{{ $event->title }}">
                    <div class="card-body d-flex flex-column">
                        <p class="card-date text-muted small">{{ date('d/m/Y', strtotime($event->date)) }}</p>
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text small mb-4">{{ count($event->users) }} Participantes</p>
                        <a href="/events/{{ $event->id }}" class="btn btn-primary mt-auto">Saber Mais</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                @if ($search)
                    <p class="lead">Não foi possível encontrar nenhum evento com "{{ $search }}". <a href="/" class="text-decoration-none">Ver todos os eventos.</a></p>
                @else
                    <p class="lead">Não há eventos disponíveis no momento.</p>
                @endif
            </div>
        @endforelse
    </div>
</div>

@endsection