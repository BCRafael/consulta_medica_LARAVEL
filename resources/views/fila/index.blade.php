@extends('layouts.app')

@section('title', 'Fila de espera e consultas')

@section('content')
    <h1>Fila de espera</h1>
    <p class="muted">Pacientes aguardando ordenados por prioridade e horário de entrada.</p>
    <div class="actions">
        <a class="button" href="{{ route('pacientes.create') }}">Adicionar paciente</a>
        <a class="button secondary" href="{{ route('doencas.create') }}">Adicionar doença</a>
        <a class="button secondary" href="{{ route('medicos.index') }}">Ver médicos</a>
    </div>

    <section class="panel">
        <h2>Pesquisar pacientes</h2>
        <form class="inline-form" method="GET" action="{{ route('fila.index') }}">
            <input type="text" name="filtro_nome" aria-label="Nome do paciente" placeholder="Nome do paciente" value="{{ $filtros['filtro_nome'] ?? '' }}">
            <select name="filtro_situacao" aria-label="Situação">
                <option value="0">Todas as situações</option>
                @foreach ($situacoes as $situacao)
                    <option value="{{ $situacao->id }}" @selected(($filtros['filtro_situacao'] ?? '') == $situacao->id)>{{ $situacao->nome }}</option>
                @endforeach
            </select>
            <select name="filtro_doenca" aria-label="Doença">
                <option value="0">Todas as doenças</option>
                @foreach ($doencas as $doenca)
                    <option value="{{ $doenca->id }}" @selected(($filtros['filtro_doenca'] ?? '') == $doenca->id)>{{ $doenca->nome }}</option>
                @endforeach
            </select>
            <button type="submit">Buscar</button>
            <a href="{{ route('fila.index') }}">Limpar</a>
        </form>
    </section>

    <section>
        <h2>Pacientes aguardando ({{ $fila->count() }})</h2>
        @if ($fila->isEmpty())
            <p class="panel">Nenhum paciente encontrado com os filtros informados.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Posição</th><th>Paciente</th><th>Doenças</th><th>Situação</th><th>Entrada</th><th>Marcar consulta</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($fila as $index => $entrada)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $entrada->paciente->nome }}</td>
                                <td>{{ $entrada->paciente->doencas->pluck('nome')->join(', ') ?: 'Sem doença' }}</td>
                                <td>{{ $entrada->situacao->nome }}</td>
                                <td>{{ $entrada->data_entrada->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if ($medicos->isEmpty())
                                        <span class="muted">Cadastre um médico para marcar.</span>
                                    @else
                                        <form class="inline-form" method="POST" action="{{ route('consultas.store') }}">
                                            @csrf
                                            <input type="hidden" name="id_fila" value="{{ $entrada->id }}">
                                            <select name="id_medico" aria-label="Médico para {{ $entrada->paciente->nome }}" required>
                                                <option value="">Selecione</option>
                                                @foreach ($medicos as $medico)
                                                    <option value="{{ $medico->id }}">{{ $medico->nome }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit">Marcar</button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    <div class="inline-form">
                                        <a href="{{ route('pacientes.edit', $entrada->paciente) }}">Editar</a>
                                        <form method="POST" action="{{ route('pacientes.destroy', $entrada->paciente) }}" onsubmit="return confirm('Excluir este paciente e seu histórico?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="danger" type="submit">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="panel" style="margin-top: 28px">
        <h2>Pesquisar consultas marcadas</h2>
        <form class="inline-form" method="GET" action="{{ route('fila.index') }}">
            <input type="text" name="consulta_nome" aria-label="Paciente" placeholder="Paciente" value="{{ $filtros['consulta_nome'] ?? '' }}">
            <input type="text" name="consulta_medico" aria-label="Médico" placeholder="Médico" value="{{ $filtros['consulta_medico'] ?? '' }}">
            <select name="consulta_situacao" aria-label="Situação da consulta">
                <option value="0">Todas as situações</option>
                @foreach ($situacoes as $situacao)
                    <option value="{{ $situacao->id }}" @selected(($filtros['consulta_situacao'] ?? '') == $situacao->id)>{{ $situacao->nome }}</option>
                @endforeach
            </select>
            <button type="submit">Buscar</button>
            <a href="{{ route('fila.index') }}">Limpar</a>
        </form>
    </section>

    <section>
        <h2>Consultas marcadas ({{ $consultas->count() }})</h2>
        @if ($consultas->isEmpty())
            <p class="panel">Nenhuma consulta encontrada com os filtros informados.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Paciente</th><th>Médico</th><th>Situação</th><th>Data da consulta</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach ($consultas as $consulta)
                            <tr>
                                <td>{{ $consulta->paciente->nome }}</td>
                                <td>{{ $consulta->medico->nome }}</td>
                                <td>{{ $consulta->situacao->nome }}</td>
                                <td>{{ $consulta->data_consulta->format('d/m/Y H:i') }}</td>
                                <td>{{ $consulta->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
