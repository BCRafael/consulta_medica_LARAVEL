<?php

namespace Database\Seeders;

use App\Models\Doenca;
use App\Models\Especialidade;
use App\Models\Situacao;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['nome' => 'CRITICO', 'prioridade' => 1],
            ['nome' => 'GRAVE', 'prioridade' => 2],
            ['nome' => 'OBSERVACAO', 'prioridade' => 3],
            ['nome' => 'ESTAVEL', 'prioridade' => 4],
        ] as $situacao) {
            Situacao::firstOrCreate(['nome' => $situacao['nome']], ['prioridade' => $situacao['prioridade']]);
        }

        foreach (['Cardiologista', 'Pediatra'] as $nome) {
            Especialidade::firstOrCreate(['nome' => $nome]);
        }

        foreach (['Leptospirose', 'Tuberculose'] as $nome) {
            Doenca::firstOrCreate(['nome' => $nome]);
        }
    }
}
