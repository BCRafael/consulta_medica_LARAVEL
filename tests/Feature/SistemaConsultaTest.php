<?php

use App\Models\Consulta;
use App\Models\Doenca;
use App\Models\Especialidade;
use App\Models\FilaEspera;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Situacao;
use Illuminate\Support\Facades\DB;

function criarSituacao(string $nome = 'GRAVE', int $prioridade = 2): Situacao
{
    return Situacao::create(['nome' => $nome, 'prioridade' => $prioridade]);
}

test('a página inicial e a fila são exibidas', function () {
    $this->get('/')->assertOk()->assertSee('Acessar fila de espera');
    $this->get('/fila')->assertOk()->assertSee('Pacientes aguardando');
});

test('cadastra paciente com doenças e adiciona na fila', function () {
    $situacao = criarSituacao();
    $doenca = Doenca::create(['nome' => 'Dengue']);

    $response = $this->post(route('pacientes.store'), [
        'nome' => 'Ana Silva',
        'cpf' => '123.456.789-01',
        'id_situacao' => $situacao->id,
        'doencas' => [$doenca->id],
    ]);

    $response->assertRedirect(route('fila.index'));
    $this->assertDatabaseHas('paciente', ['nome' => 'Ana Silva', 'cpf' => '12345678901']);

    $paciente = Paciente::where('cpf', '12345678901')->firstOrFail();
    $this->assertDatabaseHas('paciente_doenca', [
        'id_paciente' => $paciente->id,
        'id_doenca' => $doenca->id,
    ]);
    $this->assertDatabaseHas('fila_espera', [
        'id_paciente' => $paciente->id,
        'id_situacao' => $situacao->id,
        'status' => 'AGUARDANDO',
    ]);
});

test('marca consulta e atualiza a situação da entrada na fila atomicamente', function () {
    $situacao = criarSituacao();
    $especialidade = Especialidade::create(['nome' => 'Clínica geral']);
    $medico = Medico::create(['nome' => 'Dr. João', 'id_especialidade' => $especialidade->id]);
    $paciente = Paciente::create(['nome' => 'Carlos Souza', 'cpf' => '12345678901']);
    $entrada = FilaEspera::create([
        'id_paciente' => $paciente->id,
        'id_situacao' => $situacao->id,
        'status' => 'AGUARDANDO',
    ]);

    $this->post(route('consultas.store'), [
        'id_fila' => $entrada->id,
        'id_medico' => $medico->id,
    ])->assertRedirect(route('fila.index'));

    $this->assertDatabaseHas('consulta', [
        'id_paciente' => $paciente->id,
        'id_medico' => $medico->id,
        'id_situacao' => $situacao->id,
        'status' => 'MARCADA',
    ]);
    $this->assertDatabaseHas('fila_espera', [
        'id' => $entrada->id,
        'id_medico' => $medico->id,
        'status' => 'ATENDIDO',
    ]);

    $this->get(route('fila.index'))
        ->assertOk()
        ->assertSee('Carlos Souza')
        ->assertSee('Dr. João')
        ->assertSee('MARCADA');
});

test('nao cria consulta para paciente que ja saiu da fila de espera', function () {
    $situacao = criarSituacao();
    $especialidade = Especialidade::create(['nome' => 'Pediatria']);
    $medico = Medico::create(['nome' => 'Dra. Maria', 'id_especialidade' => $especialidade->id]);
    $paciente = Paciente::create(['nome' => 'Luiza Costa', 'cpf' => '10987654321']);
    $entrada = FilaEspera::create([
        'id_paciente' => $paciente->id,
        'id_situacao' => $situacao->id,
        'status' => 'ATENDIDO',
    ]);

    $this->post(route('consultas.store'), [
        'id_fila' => $entrada->id,
        'id_medico' => $medico->id,
    ])->assertSessionHasErrors('id_fila');

    expect(Consulta::count())->toBe(0);
    expect(DB::table('fila_espera')->where('id', $entrada->id)->value('status'))->toBe('ATENDIDO');
});

test('exibe pacientes na fila e aceita os filtros que significam todas as opções', function () {
    $situacao = criarSituacao('CRITICO', 1);
    $doenca = Doenca::create(['nome' => 'Dengue']);
    $paciente = Paciente::create(['nome' => 'Paciente urgente', 'cpf' => '12345678901']);
    $paciente->doencas()->attach($doenca);
    FilaEspera::create([
        'id_paciente' => $paciente->id,
        'id_situacao' => $situacao->id,
        'status' => 'AGUARDANDO',
    ]);

    $this->get(route('fila.index', ['filtro_situacao' => '0', 'filtro_doenca' => '0']))
        ->assertOk()
        ->assertSee('Paciente urgente')
        ->assertSee('Dengue')
        ->assertSee('CRITICO');
});
