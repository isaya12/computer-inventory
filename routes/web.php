<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Index;
use App\Livewire\Auth\Login;
use App\Livewire\Device;
use App\Livewire\Helpdesk;
use App\Livewire\Newdevice;
use App\Livewire\Viewusers;
use App\Livewire\Devicedetails;
use App\Livewire\Userdetails;
use App\Http\Middleware\Authenticate;

Route::middleware(['authenticate'])->group(function () {
    Route::get('/', Index::class)->name('home');
    Route::get('/devices', Device::class)->name('device');
    Route::get('/devicedetails/{id}', Devicedetails::class)->name('devicedatails');
    Route::get('/newdevices', Newdevice::class)->name('newdevice');
    Route::get('/helpdesk', Helpdesk::class)->name('helpdesk');
    Route::get('/users', Viewusers::class)->name('users');
    Route::get('/users/{id}', Userdetails::class)->name('userdetails');

});

Route::get('/login', Login::class)->name('login');
