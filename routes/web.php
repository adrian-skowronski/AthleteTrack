<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\StartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AthletePanelController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventUserController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [StartController::class, 'index'])->name('start.index');
Route::get('/verify', function () {
    return view('auth.notice');
})->name('auth.notice');
Route::get('/events-view', [EventController::class, 'view'])->name('events.view');
Route::get('/trainings-view', [TrainingController::class, 'view'])->name('trainings.view');
Route::get('/trainers/{user_id}', [TrainerController::class, 'show'])->name('trainer.details');
Route::view('/error/data', 'errors.data')->name('error.data');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ATHLETE
    Route::get('/athlete-panel', [AthletePanelController::class, 'index'])->name('athlete.panel');
    Route::get('/athlete/edit', [AthleteController::class, 'edit'])->name('athlete.edit');
    Route::post('/athlete/update', [AthleteController::class, 'update'])->name('athlete.update');
    Route::get('/athlete/change-password', [AthleteController::class, 'showChangePasswordForm'])->name('athlete.changePasswordForm');
    Route::post('/athlete/change-password', [AthleteController::class, 'changePassword'])->name('athlete.changePassword');
    Route::delete('/athlete/remove-from-training', [AthleteController::class, 'removeFromTraining'])->name('athlete.removeTraining');
    Route::post('/trainings/{training_id}/sign-up', [TrainingController::class, 'signUp'])->name('training.signUp');

    // TRAINER
// TRAINER ROUTES
Route::prefix('trainer')->name('trainer.')->middleware('auth')->group(function () {

    // Panel trenera / Mój Panel
    Route::get('/panel', [TrainerController::class, 'profile'])->name('profile');

    // Lista treningów
    Route::get('/trainings', [TrainerController::class, 'trainings'])->name('trainings');

    // Tworzenie treningu
    Route::get('/trainings/create', [TrainerController::class, 'createTraining'])->name('createTraining');
    Route::post('/trainings/store', [TrainerController::class, 'storeTraining'])->name('storeTraining');

    // Edycja treningu
    Route::get('/trainings/{training_id}/edit', [TrainerController::class, 'trainingEdit'])->name('editTraining');
    Route::put('/trainings/{training_id}', [TrainerController::class, 'trainingUpdate'])->name('updateTraining');

    // Usuwanie treningu
    Route::delete('/trainings/{training_id}', [TrainerController::class, 'trainingDestroy'])->name('trainingDestroy');

// Uczestnicy treningu
Route::get('/trainings/{training_id}/participants', [TrainerController::class, 'viewParticipants'])
    ->name('participants');

Route::get('/trainings/{training_id}/participants/{user_id}/edit', [TrainerController::class, 'editStatus'])
    ->name('editStatus');

Route::patch('/trainings/{training_id}/participants/{user_id}/update', [TrainerController::class, 'updateStatus'])
    ->name('updateStatus');

Route::delete('/trainings/{training_id}/participants/{user_id}', [TrainerController::class, 'removeParticipant'])
    ->name('removeParticipant');

    // Profil trenera
    Route::get('/edit', [TrainerController::class, 'edit'])->name('edit');
    Route::post('/update', [TrainerController::class, 'update'])->name('update');

    // Zmiana hasła trenera
    Route::get('/change-password', [TrainerController::class, 'showChangePasswordForm'])->name('changePasswordForm');
    Route::post('/change-password', [TrainerController::class, 'changePassword'])->name('changePassword');

});


    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(AdminMiddleware::class)->prefix('admin')->name('admin.')->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/edit/{user}', [AdminController::class, 'edit'])->name('edit');
        Route::post('/update/{user}', [AdminController::class, 'update'])->name('update');
        Route::get('/change-password', [AdminController::class, 'showChangePasswordForm'])->name('changePasswordForm');
        Route::post('/change-password', [AdminController::class, 'changePassword'])->name('changePassword');

        // Users
        Route::get('/approve/{user}', [AdminController::class, 'approve'])->name('approve');
        Route::post('/store-approval/{user}', [AdminController::class, 'storeApproval'])->name('storeApproval');
        Route::delete('/reject/{user}', [AdminController::class, 'reject'])->name('reject');
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::resource('users', UserController::class);

        // Sports
        Route::post('/sports/{sport}/deactivate', [SportController::class, 'deactivate'])->name('sports.deactivate');
        Route::post('/sports/{sport}/activate', [SportController::class, 'activate'])->name('sports.activate');
        Route::resource('sports', SportController::class);

        // Trainings
        Route::get('/trainings/{training}/participants', [TrainingController::class, 'participants'])->name('trainings.participants');
        Route::resource('trainings', TrainingController::class);

        // Events
        Route::resource('events', EventController::class);

        // Event-User
        Route::prefix('event-user')->group(function () {
Route::get('/{event_id}', [EventUserController::class, 'show'])
    ->name('event-user.show');
            Route::get('/select-athletes/{event_id}', [EventUserController::class, 'selectAthletes'])->name('event-user.select');
            Route::post('/store', [EventUserController::class, 'store'])->name('event-user.store');
            Route::get('/edit/{event_user_id}', [EventUserController::class, 'edit'])->name('event-user.edit');
            Route::put('/update/{event_user_id}', [EventUserController::class, 'update'])->name('event-user.update');
            Route::delete('/destroy/{event_user_id}', [EventUserController::class, 'destroy'])->name('event-user.destroy');
        });

    });

});

require __DIR__.'/auth.php';
