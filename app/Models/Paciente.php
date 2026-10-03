<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $table = 'paciente';

    public $timestamps = false;

    protected $fillable = ['nome', 'cpf'];

    public function doencas(): BelongsToMany
    {
        return $this->belongsToMany(
            Doenca::class,
            'paciente_doenca',
            'id_paciente',
            'id_doenca'
        );
    }

    public function filaEspera(): HasMany
    {
        return $this->hasMany(FilaEspera::class, 'id_paciente');
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'id_paciente');
    }
}
