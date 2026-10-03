@extends('layouts.app')

@php($edicao = isset($paciente))

@section('title', $edicao ? 'Editar paciente' : 'Adicionar paciente')

@section('content')
    <h1>{{ $edicao ? 'Editar paciente' : 'Adicionar paciente à fila' }}</h1>
    <form class="panel" method="POST" action="{{ $edicao ? route('pacientes.update', $paciente) : route('pacientes.store') }}">
        @csrf
        @if ($edicao)
            @method('PUT')
        @endif
        <div class="field">
            <label for="nome">Nome</label>
            <input id="nome" name="nome" type="text" maxlength="100" value="{{ old('nome', $paciente->nome ?? '') }}" required>
        </div>
        <div class="field">
            <label for="cpf">CPF</label>
            <input id="cpf" name="cpf" type="text" inputmode="numeric" maxlength="14" placeholder="Somente os 11 dígitos" value="{{ old('cpf', $paciente->cpf ?? '') }}" required>
        </div>
        @unless ($edicao)
            <div class="field">
                <label for="id_situacao">Situação na fila</label>
                <select id="id_situacao" name="id_situacao" required>
                    <option value="">Selecione</option>
                    @foreach ($situacoes as $situacao)
                        <option value="{{ $situacao->id }}" @selected(old('id_situacao') == $situacao->id)>{{ $situacao->nome }} (prioridade {{ $situacao->prioridade }})</option>
                    @endforeach
                </select>
            </div>
        @endunless
        <div class="field checkboxes">
            <label>Doenças</label>
            @php($selecionadas = old('doencas', isset($paciente) ? $paciente->doencas->pluck('id')->all() : []))
            @forelse ($doencas as $doenca)
                <label>
                    <input type="checkbox" name="doencas[]" value="{{ $doenca->id }}" @checked(in_array($doenca->id, $selecionadas))>
                    {{ $doenca->nome }}
                </label><br>
            @empty
                <p class="muted">Nenhuma doença cadastrada. Você pode adicionar doenças pela página da fila.</p>
            @endforelse
        </div>
        <div class="actions">
            <button type="submit">{{ $edicao ? 'Salvar alterações' : 'Adicionar paciente' }}</button>
            <a class="button secondary" href="{{ route('fila.index') }}">Cancelar</a>
        </div>
    </form>
@endsection
