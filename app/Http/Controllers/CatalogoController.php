<?php

namespace App\Http\Controllers;

use App\Models\Doenca;
use App\Models\Especialidade;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function createDoenca()
    {
        return view('catalogo.form', [
            'titulo' => 'Adicionar doença',
            'campo' => 'doença',
            'action' => route('doencas.store'),
            'voltar' => route('fila.index'),
        ]);
    }

    public function storeDoenca(Request $request)
    {
        $dados = $request->validate(['nome' => ['required', 'string', 'max:100', 'unique:doenca,nome']]);
        Doenca::create(['nome' => trim($dados['nome'])]);

        return redirect()->route('fila.index')->with('success', 'Doença adicionada com sucesso.');
    }

    public function createEspecialidade()
    {
        return view('catalogo.form', [
            'titulo' => 'Adicionar especialidade',
            'campo' => 'especialidade',
            'action' => route('especialidades.store'),
            'voltar' => route('medicos.index'),
        ]);
    }

    public function storeEspecialidade(Request $request)
    {
        $dados = $request->validate(['nome' => ['required', 'string', 'max:100', 'unique:especialidade,nome']]);
        Especialidade::create(['nome' => trim($dados['nome'])]);

        return redirect()->route('medicos.index')->with('success', 'Especialidade adicionada com sucesso.');
    }
}
