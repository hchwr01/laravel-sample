<?php

use App\Livewire\Todo\TodoIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ToDo
Route::group(['prefix' => 'todos'], function () {
    Route::get('/', TodoIndex::class)->name('todos.index');
});