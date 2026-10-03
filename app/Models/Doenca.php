<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doenca extends Model
{
    protected $table = 'doenca';

    public $timestamps = false;

    protected $fillable = ['nome'];

    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(
            Paciente::class,
            'paciente_doenca',
            'id_doenca',
            'id_paciente'
        );
    }
}
