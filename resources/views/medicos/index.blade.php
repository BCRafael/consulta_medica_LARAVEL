@extends('layouts.app')

@section('title', 'Médicos')

@section('content')
    <h1>Médicos</h1>
    <div class="actions">
        <a class="button" href="{{ route('medicos.create') }}">Adicionar médico</a>
        <a class="button secondary" href="{{ route('especialidades.create') }}">Adicionar especialidade</a>
        <a class="button secondary" href="{{ route('fila.index') }}">Voltar para a fila</a>
    </div>
    <section class="panel">
        <h2>Pesquisar médicos</h2>
        <form class="inline-form" method="GET" action="{{ route('medicos.index') }}">
            <input type="text" name="filtro_nome" aria-label="Nome" placeholder="Nome" value="{{ $filtros['filtro_nome'] ?? '' }}">
            <select name="filtro_especialidade" aria-label="Especialidade">
                <option value="0">Todas as especialidades</option>
                @foreach ($especialidades as $especialidade)
                    <option value="{{ $especialidade->id }}" @selected(($filtros['filtro_especialidade'] ?? '') == $especialidade->id)>{{ $especialidade->nome }}</option>
                @endforeach
            </select>
            <button type="submit">Buscar</button>
            <a href="{{ route('medicos.index') }}">Limpar</a>
        </form>
    </section>
    @if ($medicos->isEmpty())
        <p class="panel">Nenhum médico encontrado com os filtros informados.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Nome</th><th>Especialidade</th><th>Ações</th></tr></thead>
                <tbody>
                    @foreach ($medicos as $medico)
                        <tr>
                            <td>{{ $medico->id }}</td>
                            <td>{{ $medico->nome }}</td>
                            <td>{{ $medico->especialidade?->nome ?? 'Sem especialidade' }}</td>
                            <td>
                                <div class="inline-form">
                                    <a href="{{ route('medicos.edit', $medico) }}">Editar</a>
                                    <form method="POST" action="{{ route('medicos.destroy', $medico) }}" onsubmit="return confirm('Excluir este médico e as consultas relacionadas?')">
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
@endsection
