<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\FilaEspera;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AtendimentoController extends Controller
{
    public function store(Request $request)
    {
        $dados = $request->validate([
            'id_fila' => ['required', 'integer', Rule::exists('fila_espera', 'id')->where('status', 'AGUARDANDO')],
            'id_medico' => ['required', 'integer', 'exists:medico,id'],
        ]);

        $marcada = DB::transaction(function () use ($dados): bool {
            $entrada = FilaEspera::query()->lockForUpdate()->find($dados['id_fila']);

            if (! $entrada || $entrada->status !== 'AGUARDANDO' || ! Medico::query()->whereKey($dados['id_medico'])->exists()) {
                return false;
            }

            Consulta::create([
                'id_paciente' => $entrada->id_paciente,
                'id_medico' => $dados['id_medico'],
                'id_situacao' => $entrada->id_situacao,
                'data_consulta' => now(),
                'status' => 'MARCADA',
            ]);

            $entrada->update([
                'status' => 'ATENDIDO',
                'id_medico' => $dados['id_medico'],
            ]);

            return true;
        });

        if (! $marcada) {
            return redirect()->route('fila.index')->with('error', 'Esse paciente não está mais aguardando atendimento.');
        }

        return redirect()->route('fila.index')->with('success', 'Consulta marcada com sucesso.');
    }
}
