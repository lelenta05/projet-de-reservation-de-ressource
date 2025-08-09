<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome')->name('home');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/reservation','reservations.index')->name('reservations.index');
Route::view('/ressources','ressources.index')->name('ressources.index');