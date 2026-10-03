<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Situacao extends Model
{
    protected $table = 'situacao';

    public $timestamps = false;

    protected $fillable = ['nome', 'prioridade'];

    public function filaEspera(): HasMany
    {
        return $this->hasMany(FilaEspera::class, 'id_situacao');
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'id_situacao');
    }
}
