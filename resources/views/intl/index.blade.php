@extends('layouts.index')
@section('title', 'Nido del Pajaro')
@section('content')
    <h1>TORNEOS INTERNACIONALES</h1>
    <a class="nav-link" href="/">Regresar</a>
    <ul class="list-group">
    @foreach ($tournaments as $torneo)
      <li class="list-group-item"><a href="intl/torneo/{{$torneo->id}}">{{$torneo->name}}</a></li>
    @endforeach
    </ul>
@endsection
