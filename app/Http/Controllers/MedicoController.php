<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use App\Models\Medico;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'filtro_nome' => ['nullable', 'string', 'max:100'],
            'filtro_especialidade' => ['nullable', 'integer', 'exists:especialidade,id'],
        ]);

        $medicos = Medico::query()
            ->with('especialidade')
            ->when($filtros['filtro_nome'] ?? null, fn (Builder $query, string $nome) => $query->where('nome', 'like', "%{$nome}%"))
            ->when($filtros['filtro_especialidade'] ?? null, fn (Builder $query, int $id) => $query->where('id_especialidade', $id))
            ->orderBy('nome')
            ->get();

        return view('medicos.index', [
            'medicos' => $medicos,
            'especialidades' => Especialidade::query()->orderBy('nome')->get(),
            'filtros' => $filtros,
        ]);
    }

    public function create()
    {
        return view('medicos.form', [
            'especialidades' => Especialidade::query()->orderBy('nome')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'id_especialidade' => ['required', 'integer', 'exists:especialidade,id'],
        ]);

        Medico::create(['nome' => trim($dados['nome']), 'id_especialidade' => $dados['id_especialidade']]);

        return redirect()->route('medicos.index')->with('success', 'Médico adicionado com sucesso.');
    }

    public function edit(Medico $medico)
    {
        return view('medicos.form', [
            'medico' => $medico,
            'especialidades' => Especialidade::query()->orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Medico $medico)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'id_especialidade' => ['required', 'integer', 'exists:especialidade,id'],
        ]);

        $medico->update(['nome' => trim($dados['nome']), 'id_especialidade' => $dados['id_especialidade']]);

        return redirect()->route('medicos.index')->with('success', 'Médico atualizado com sucesso.');
    }

    public function destroy(Medico $medico)
    {
        DB::transaction(function () use ($medico): void {
            $medico->consultas()->delete();
            $medico->filaEspera()->delete();
            $medico->delete();
        });

        return redirect()->route('medicos.index')->with('success', 'Médico e consultas relacionadas excluídos com sucesso.');
    }
}
