@extends('layouts.app')

@section('title', $titulo)

@section('content')
    <h1>{{ $titulo }}</h1>
    <form class="panel" method="POST" action="{{ $action }}">
        @csrf
        <div class="field">
            <label for="nome">Nome da {{ $campo }}</label>
            <input id="nome" name="nome" type="text" maxlength="100" value="{{ old('nome') }}" required>
        </div>
        <div class="actions">
            <button type="submit">Salvar</button>
            <a class="button secondary" href="{{ $voltar }}">Cancelar</a>
        </div>
    </form>
@endsection
