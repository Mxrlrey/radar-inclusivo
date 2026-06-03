@extends('layouts.master')

@section('content')
    <h1>Relatórios</h1>

    <p>Módulo de relatórios do sistema.</p>

    @can('report.index')
        <a href="{{ route('relatorios.criar') }}" class="btn btn-primary">
            Testar Builder
        </a>
    @endcan
@endsection
