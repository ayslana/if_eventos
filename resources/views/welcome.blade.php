@extends('layouts.main')

@section('title','HDC Events')

@section('content')

        <h1>Título</h1>
        <img src="/img/banner.jpeg" alt="Banner">
        @if(10 > 15) 
            <p>A condição é true</p>
        @endif

@endsection