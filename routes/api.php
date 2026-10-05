<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoApiController;

Route::get('/cursos', [CursoApiController::class, 'index']);
Route::get('/cursos/{id}', [CursoApiController::class, 'show']);
