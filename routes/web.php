<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\NoteController;

Route::get('/', function () {
});

Route::controller(AuthenticationController::class)
    ->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('login.post');
        Route::get('/register', 'showRegisterForm')->name('register');
        Route::post('/register', 'register')->name('register.post');
        Route::get('/logout', 'logout')->name('logout');
});

Route::get('/dashboard', [NoteController::class, 'showNotes'])
    ->name('dashboard');

Route::controller(NoteController::class)
    ->middleware('auth')
    ->prefix('notes')
    ->name('notes.')
    ->group(function () {
        Route::post('/create', 'createNote')->name('create');
        Route::put('/{note}/update', 'updateContent')->name('update');
        Route::delete('/{note}/delete', 'deleteNote')->name('delete');
    });