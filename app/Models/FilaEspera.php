<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilaEspera extends Model
{
    protected $table = 'fila_espera';

    public $timestamps = false;

    protected $fillable = ['id_paciente', 'id_situacao', 'id_medico', 'data_entrada', 'status'];

    protected function casts(): array
    {
        return ['data_entrada' => 'datetime'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(Situacao::class, 'id_situacao');
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }
}
