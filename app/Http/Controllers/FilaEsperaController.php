<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Doenca;
use App\Models\FilaEspera;
use App\Models\Medico;
use App\Models\Situacao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FilaEsperaController extends Controller
{
    public function index(Request $request)
    {
        $filtrosFila = $request->validate([
            'filtro_nome' => ['nullable', 'string', 'max:100'],
            'filtro_situacao' => ['nullable', 'integer', 'min:0'],
            'filtro_doenca' => ['nullable', 'integer', 'min:0'],
            'consulta_nome' => ['nullable', 'string', 'max:100'],
            'consulta_medico' => ['nullable', 'string', 'max:100'],
            'consulta_situacao' => ['nullable', 'integer', 'min:0'],
        ]);

        $fila = FilaEspera::query()
            ->with(['paciente.doencas', 'situacao'])
            ->join('situacao', 'fila_espera.id_situacao', '=', 'situacao.id')
            ->select('fila_espera.*')
            ->where('fila_espera.status', 'AGUARDANDO')
            ->when($filtrosFila['filtro_nome'] ?? null, function (Builder $query, string $nome): void {
                $query->whereHas('paciente', fn (Builder $pacientes) => $pacientes->where('nome', 'like', "%{$nome}%"));
            })
            ->when($filtrosFila['filtro_situacao'] ?? null, fn (Builder $query, int $id) => $query->where('fila_espera.id_situacao', $id))
            ->when($filtrosFila['filtro_doenca'] ?? null, function (Builder $query, int $id): void {
                $query->whereHas('paciente.doencas', fn (Builder $doencas) => $doencas->where('doenca.id', $id));
            })
            ->orderBy('situacao.prioridade')
            ->orderBy('fila_espera.data_entrada')
            ->get();

        $consultas = Consulta::query()
            ->with(['paciente', 'medico', 'situacao'])
            ->when($filtrosFila['consulta_nome'] ?? null, function (Builder $query, string $nome): void {
                $query->whereHas('paciente', fn (Builder $pacientes) => $pacientes->where('nome', 'like', "%{$nome}%"));
            })
            ->when($filtrosFila['consulta_medico'] ?? null, function (Builder $query, string $nome): void {
                $query->whereHas('medico', fn (Builder $medicos) => $medicos->where('nome', 'like', "%{$nome}%"));
            })
            ->when($filtrosFila['consulta_situacao'] ?? null, fn (Builder $query, int $id) => $query->where('id_situacao', $id))
            ->orderByDesc('data_consulta')
            ->get();

        return view('fila.index', [
            'fila' => $fila,
            'consultas' => $consultas,
            'medicos' => Medico::query()->orderBy('nome')->get(),
            'situacoes' => Situacao::query()->orderBy('prioridade')->get(),
            'doencas' => Doenca::query()->orderBy('nome')->get(),
            'filtros' => $filtrosFila,
        ]);
    }
}
