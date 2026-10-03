<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consulta extends Model
{
    protected $table = 'consulta';

    public $timestamps = false;

    protected $fillable = ['id_paciente', 'id_medico', 'id_situacao', 'data_consulta', 'status'];

    protected function casts(): array
    {
        return ['data_consulta' => 'datetime'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'id_medico');
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(Situacao::class, 'id_situacao');
    }
}
