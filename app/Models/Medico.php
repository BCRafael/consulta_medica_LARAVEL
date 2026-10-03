<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medico extends Model
{
    protected $table = 'medico';

    public $timestamps = false;

    protected $fillable = ['nome', 'id_especialidade'];

    public function especialidade(): BelongsTo
    {
        return $this->belongsTo(Especialidade::class, 'id_especialidade');
    }

    public function filaEspera(): HasMany
    {
        return $this->hasMany(FilaEspera::class, 'id_medico');
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'id_medico');
    }
}
