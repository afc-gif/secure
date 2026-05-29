<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AccessTokenController as AdminAccessTokenController;
use App\Http\Controllers\Admin\BatchController as AdminBatchController;
use App\Http\Controllers\Member\AccessTokenController as MemberAccessTokenController;
use App\Http\Controllers\Member\BatchController as MemberBatchController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\ParticipationController as MemberParticipationController;
use App\Http\Controllers\Onboarding\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Support\AuthRedirector;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect(AuthRedirector::pathFor(request()->user()));
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::resource('batches', AdminBatchController::class)->except(['show', 'destroy']);
    Route::patch('/batches/{batch}/archive', [AdminBatchController::class, 'archive'])->name('batches.archive');
    Route::get('/tokens', [AdminAccessTokenController::class, 'index'])->name('tokens.index');
    Route::get('/tokens/create', [AdminAccessTokenController::class, 'create'])->name('tokens.create');
    Route::post('/tokens', [AdminAccessTokenController::class, 'store'])->name('tokens.store');
    Route::patch('/tokens/{token}/revoke', [AdminAccessTokenController::class, 'revoke'])->name('tokens.revoke');
});

Route::middleware(['auth', 'role:member'])->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/', [OnboardingController::class, 'index'])->name('index');
    Route::get('/step/{step}', [OnboardingController::class, 'step'])->whereNumber('step')->name('step');
    Route::post('/step/1', [OnboardingController::class, 'storeIdentity'])->name('step.identity.store');
    Route::post('/step/2', [OnboardingController::class, 'storeAddress'])->name('step.address.store');
    Route::post('/step/3', [OnboardingController::class, 'storeCooperativeProfile'])->name('step.cooperative.store');
    Route::get('/review', [OnboardingController::class, 'review'])->name('review');
    Route::post('/review', [OnboardingController::class, 'complete'])->name('complete');
});

Route::middleware(['auth', 'role:member', 'onboarded'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', MemberDashboardController::class)->name('dashboard');
    Route::get('/batches', [MemberBatchController::class, 'index'])->name('batches.index');
    Route::get('/access-token', [MemberAccessTokenController::class, 'create'])->name('access-token.create');
    Route::post('/access-token', [MemberAccessTokenController::class, 'store'])->name('access-token.store');
    Route::get('/participation', [MemberParticipationController::class, 'index'])->name('participation.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
