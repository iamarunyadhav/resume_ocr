<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Wizard\Step1Upload;
use App\Livewire\Wizard\Step2Review;
use App\Livewire\Wizard\Step3Suggestions;
use App\Livewire\Wizard\Step4Preview;

Route::get('/', Step1Upload::class)->name('wizard.step1');
Route::get('/review/{document}', Step2Review::class)->name('wizard.step2');
Route::get('/suggestions/{document}', Step3Suggestions::class)->name('wizard.step3');
Route::get('/preview/{document}', Step4Preview::class)->name('wizard.step4');

// Route::get('/wizard/step4/{document}', \App\Livewire\Wizard\Step4Preview::class)
//     ->name('wizard.step4');
