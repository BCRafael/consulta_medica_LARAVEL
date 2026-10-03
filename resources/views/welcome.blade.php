@extends('layouts.app')

@section('title', 'Sistema de Saúde')

@section('content')
    <section class="hero">
        <p class="eyebrow">Gestão de atendimento</p>
        <h1>Sistema de Saúde</h1>
        <p>Organize a fila de espera, cadastre pacientes e médicos e acompanhe as consultas da unidade.</p>
        <a class="button" href="{{ route('fila.index') }}">Acessar fila de espera</a>
    </section>
    <section class="grid">
        <article class="card">
            <h2>Fila por prioridade</h2>
            <p>Visualize pacientes aguardando, considerando a prioridade da situação e o horário de entrada.</p>
        </article>
        <article class="card">
            <h2>Cadastros</h2>
            <p>Gerencie pacientes, doenças, médicos e especialidades em um só lugar.</p>
        </article>
        <article class="card">
            <h2>Consultas</h2>
            <p>Marque atendimentos a partir da fila e pesquise o histórico das consultas.</p>
        </article>
    </section>
@endsection
