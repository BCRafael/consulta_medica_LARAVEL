<?php

use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\FilaEsperaController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/fila', [FilaEsperaController::class, 'index'])->name('fila.index');

Route::get('/pacientes/novo', [PacienteController::class, 'create'])->name('pacientes.create');
Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
Route::get('/pacientes/{paciente}/editar', [PacienteController::class, 'edit'])->name('pacientes.edit');
Route::put('/pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');
Route::delete('/pacientes/{paciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');

Route::get('/medicos', [MedicoController::class, 'index'])->name('medicos.index');
Route::get('/medicos/novo', [MedicoController::class, 'create'])->name('medicos.create');
Route::post('/medicos', [MedicoController::class, 'store'])->name('medicos.store');
Route::get('/medicos/{medico}/editar', [MedicoController::class, 'edit'])->name('medicos.edit');
Route::put('/medicos/{medico}', [MedicoController::class, 'update'])->name('medicos.update');
Route::delete('/medicos/{medico}', [MedicoController::class, 'destroy'])->name('medicos.destroy');

Route::get('/doencas/nova', [CatalogoController::class, 'createDoenca'])->name('doencas.create');
Route::post('/doencas', [CatalogoController::class, 'storeDoenca'])->name('doencas.store');
Route::get('/especialidades/nova', [CatalogoController::class, 'createEspecialidade'])->name('especialidades.create');
Route::post('/especialidades', [CatalogoController::class, 'storeEspecialidade'])->name('especialidades.store');

Route::post('/consultas', [AtendimentoController::class, 'store'])->name('consultas.store');
