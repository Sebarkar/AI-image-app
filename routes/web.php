<?php

use Illuminate\Support\Facades\Route;

//VERIFICATION ROUTES
Route::get('/email/verify/{id}/{hash}', function (string $id, string $hash) {
    return redirect(config('1settings.email_verification') . $id . '/' . $hash);
})->middleware(['signed'])->name('verification.verify');
