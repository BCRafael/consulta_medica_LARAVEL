@extends('layouts.app')

@php($edicao = isset($medico))

@section('title', $edicao ? 'Editar médico' : 'Adicionar médico')

@section('content')
    <h1>{{ $edicao ? 'Editar médico' : 'Adicionar médico' }}</h1>
    <form class="panel" method="POST" action="{{ $edicao ? route('medicos.update', $medico) : route('medicos.store') }}">
        @csrf
        @if ($edicao)
            @method('PUT')
        @endif
        <div class="field">
            <label for="nome">Nome</label>
            <input id="nome" name="nome" type="text" maxlength="100" value="{{ old('nome', $medico->nome ?? '') }}" required>
        </div>
        <div class="field">
            <label for="id_especialidade">Especialidade</label>
            <select id="id_especialidade" name="id_especialidade" required>
                <option value="">Selecione</option>
                @foreach ($especialidades as $especialidade)
                    <option value="{{ $especialidade->id }}" @selected(old('id_especialidade', $medico->id_especialidade ?? '') == $especialidade->id)>{{ $especialidade->nome }}</option>
                @endforeach
            </select>
            @if ($especialidades->isEmpty())
                <p class="muted">Cadastre uma especialidade antes de adicionar o médico.</p>
                <a href="{{ route('especialidades.create') }}">Adicionar especialidade</a>
            @endif
        </div>
        <div class="actions">
            <button type="submit">{{ $edicao ? 'Salvar alterações' : 'Adicionar médico' }}</button>
            <a class="button secondary" href="{{ route('medicos.index') }}">Cancelar</a>
        </div>
    </form>
@endsection
