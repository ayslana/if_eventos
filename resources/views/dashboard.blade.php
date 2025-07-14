@extends('layouts.main')

@section('title', 'Meu Dashboard')

@section('content')

<div class="container my-5">
    <div class="dashboard-title-container mb-4">
        <h1>Meus Eventos</h1>
    </div>

    <div class="dashboard-events-container">
        @if (count($events) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Participantes</th>
                            <th scope="col" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td scope="row">{{ $loop->iteration }}</td>
                                <td><a href="/events/{{ $event->id }}">{{ $event->title }}</a></td>
                                <td>{{ count($event->users) }}</td>
                                <td class="text-center">
                                    <a href="/events/edit/{{ $event->id }}" class="btn btn-info btn-sm edit-btn">
                                        <ion-icon name="create-outline"></ion-icon> Editar
                                    </a>
                                    <form action="/events/{{ $event->id }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                            <ion-icon name="trash-outline"></ion-icon> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="lead">Você ainda não criou nenhum evento. <a href="/events/create">Que tal criar um agora?</a></p>
        @endif
    </div>
</div>

@endsection