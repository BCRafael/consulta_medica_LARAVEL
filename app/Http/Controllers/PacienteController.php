<?php

namespace App\Http\Controllers;

use App\Models\Doenca;
use App\Models\Paciente;
use App\Models\Situacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    public function create()
    {
        return view('pacientes.form', [
            'doencas' => Doenca::query()->orderBy('nome')->get(),
            'situacoes' => Situacao::query()->orderBy('prioridade')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->normalizeCpf($request);
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'digits:11', 'unique:paciente,cpf'],
            'id_situacao' => ['required', 'integer', 'exists:situacao,id'],
            'doencas' => ['nullable', 'array'],
            'doencas.*' => ['integer', 'distinct', 'exists:doenca,id'],
        ]);

        DB::transaction(function () use ($dados): void {
            $paciente = Paciente::create([
                'nome' => trim($dados['nome']),
                'cpf' => $dados['cpf'],
            ]);
            $paciente->doencas()->sync($dados['doencas'] ?? []);
            $paciente->filaEspera()->create([
                'id_situacao' => $dados['id_situacao'],
                'status' => 'AGUARDANDO',
            ]);
        });
        
        return redirect()->route('fila.index')->with('success', 'Paciente adicionado à fila de espera.');
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.form', [
            'paciente' => $paciente->load('doencas'),
            'doencas' => Doenca::query()->orderBy('nome')->get(),
            'situacoes' => collect(),
        ]);
    }

    public function update(Request $request, Paciente $paciente)
    {
        $this->normalizeCpf($request);
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'digits:11', Rule::unique('paciente', 'cpf')->ignore($paciente->id)],
            'doencas' => ['nullable', 'array'],
            'doencas.*' => ['integer', 'distinct', 'exists:doenca,id'],
        ]);

        DB::transaction(function () use ($dados, $paciente): void {
            $paciente->update([
                'nome' => trim($dados['nome']),
                'cpf' => $dados['cpf'],
            ]);
            $paciente->doencas()->sync($dados['doencas'] ?? []);
        });

        return redirect()->route('fila.index')->with('success', 'Paciente atualizado com sucesso.');
    }

    public function destroy(Paciente $paciente)
    {
        DB::transaction(function () use ($paciente): void {
            $paciente->doencas()->detach();
            $paciente->filaEspera()->delete();
            $paciente->consultas()->delete();
            $paciente->delete();
        });

        return redirect()->route('fila.index')->with('success', 'Paciente excluído com sucesso.');
    }

    private function normalizeCpf(Request $request): void
    {
        $cpf = $request->input('cpf');
        $request->merge(['cpf' => is_string($cpf) ? preg_replace('/\D/', '', $cpf) : $cpf]);
    }
}
