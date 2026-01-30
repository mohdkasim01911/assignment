<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [ShortUrlController::class,'dashboard'])->name('dashboard');

   Route::resource('shorturls', ShortUrlController::class);
   Route::get('genarte-short-url', [ShortUrlController::class,'generate_url'])->name('generate.url');
   Route::post('genarte-short-url-store', [ShortUrlController::class,'generate_url_store'])->name('generate.url.store');

   Route::get('/s/{code}', [ShortUrlController::class, 'redirect']);

   Route::post('download-urls', [ShortUrlController::class, 'downloadUrl'])->name('download.urls');
   Route::get('all-member', [ShortUrlController::class, 'allMember'])->name('view.all.member');
   Route::get('all-urls', [ShortUrlController::class, 'allUrls'])->name('view.all.urls');
    

});

require __DIR__.'/auth.php';
