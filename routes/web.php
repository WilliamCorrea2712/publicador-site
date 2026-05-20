<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'showPublic'])->name('home');
Route::get('/contratar', [LandingPageController::class, 'showRegisterForm'])->name('contratar');
Route::post('/contratar', [LandingPageController::class, 'registerCustomer'])->name('contratar.store');
Route::get('/loja/{slug}', [LandingPageController::class, 'showCustomerPage'])->name('customer.page');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.customers');
        }
        return redirect()->route('landing.edit');
    })->name('dashboard');

    Route::get('/minha-pagina', [LandingPageController::class, 'index'])->name('landing.edit');
    Route::get('/minha-pagina/criar', [LandingPageController::class, 'create'])->name('landing.create');
    Route::post('/minha-pagina', [LandingPageController::class, 'store'])->name('landing.store');
    Route::get('/minha-pagina/{page}/editar', [LandingPageController::class, 'editPage'])->name('landing.page.edit');
    Route::put('/minha-pagina/{page}', [LandingPageController::class, 'update'])->name('landing.update');

    Route::get('/admin/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/admin/customers/{customer}', [AdminController::class, 'showCustomer'])->name('admin.customer.show');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/admin/password', [AdminController::class, 'changePassword'])->name('admin.password.change');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
