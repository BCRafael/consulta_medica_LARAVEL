<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('especialidade', function (Blueprint $table): void {
            $table->id();
            $table->string('nome', 100)->unique();
        });

        Schema::create('medico', function (Blueprint $table): void {
            $table->id();
            $table->string('nome', 100);
            $table->foreignId('id_especialidade')->nullable()->constrained('especialidade');
        });

        Schema::create('doenca', function (Blueprint $table): void {
            $table->id();
            $table->string('nome', 100)->unique();
        });

        Schema::create('paciente', function (Blueprint $table): void {
            $table->id();
            $table->string('nome', 100);
            $table->string('cpf', 11)->unique();
        });

        Schema::create('paciente_doenca', function (Blueprint $table): void {
            $table->foreignId('id_paciente')->constrained('paciente');
            $table->foreignId('id_doenca')->constrained('doenca');
            $table->primary(['id_paciente', 'id_doenca']);
        });

        Schema::create('situacao', function (Blueprint $table): void {
            $table->id();
            $table->string('nome', 20)->unique();
            $table->unsignedInteger('prioridade')->unique();
        });

        Schema::create('fila_espera', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('id_paciente')->constrained('paciente');
            $table->foreignId('id_situacao')->constrained('situacao');
            $table->foreignId('id_medico')->nullable()->constrained('medico');
            $table->timestamp('data_entrada')->useCurrent();
            $table->string('status', 20)->default('AGUARDANDO');
        });

        Schema::create('consulta', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('id_paciente')->constrained('paciente');
            $table->foreignId('id_medico')->constrained('medico');
            $table->foreignId('id_situacao')->constrained('situacao');
            $table->timestamp('data_consulta')->useCurrent();
            $table->string('status', 20)->default('MARCADA');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consulta');
        Schema::dropIfExists('fila_espera');
        Schema::dropIfExists('situacao');
        Schema::dropIfExists('paciente_doenca');
        Schema::dropIfExists('paciente');
        Schema::dropIfExists('doenca');
        Schema::dropIfExists('medico');
        Schema::dropIfExists('especialidade');
    }
};
