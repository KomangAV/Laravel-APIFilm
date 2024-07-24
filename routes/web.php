<?php

use App\Http\Controllers\MovieController;

Route::get('/', function () {
    return redirect('/movies');
});

Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
