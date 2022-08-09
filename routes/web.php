<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/register', [App\Http\Controllers\HomeController::class, 'register'])->name('index.register');
Route::post('/register', [App\Http\Controllers\HomeController::class, 'register_save'])->name('index.register.save');
Route::get('/register/{id}/{locale}', [App\Http\Controllers\HomeController::class, 'register_verify'])->whereNumber('id')->name('index.register.verify');
Route::get('/register/update/{id}/{locale}', [App\Http\Controllers\HomeController::class, 'register_update'])->whereNumber('id')->name('index.register.update');
Route::post('/register/update/{id}/{locale}', [App\Http\Controllers\HomeController::class, 'register_update_save'])->whereNumber('id')->name('index.register.update.save');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/admin', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    /*
     * Authentication routes
     */
    Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);
    /*
     * Participants controller routes
     */
    Route::get('/api/participants', [App\Http\Controllers\Api\ParticipantController::class, 'all'])->name('api.participants.all');
    Route::get('/api/participants/{id}', [App\Http\Controllers\Api\ParticipantController::class, 'edit'])->whereNumber('id')->name('api.participants.edit');
    Route::post('/api/participants', [App\Http\Controllers\Api\ParticipantController::class, 'create'])->name('api.participants.create');
    Route::put('/api/participants/{id}', [App\Http\Controllers\Api\ParticipantController::class, 'save'])->whereNumber('id')->name('api.participants.save');
    Route::delete('/api/participants/{id}', [App\Http\Controllers\Api\ParticipantController::class, 'delete'])->whereNumber('id')->name('api.participants.delete');
    Route::get('/api/participants/validate/email', [App\Http\Controllers\Api\ParticipantController::class, 'validate_email'])->name('api.participants.validate.email');
    Route::get('/api/participants/statistics', [App\Http\Controllers\Api\ParticipantController::class, 'statistics'])->name('api.participants.statistics');
    Route::get('/api/participants/send/link/{id}', [App\Http\Controllers\Api\ParticipantController::class, 'send_link'])->whereNumber('id')->name('api.participants.send.link');
    /*
     * Studies controller routes
     */
    Route::get('/api/studies', [App\Http\Controllers\Api\StudyController::class, 'all'])->name('api.studies.all');
    Route::get('/api/studies/{id}', [App\Http\Controllers\Api\StudyController::class, 'edit'])->whereNumber('id')->name('api.studies.edit');
    Route::post('/api/studies', [App\Http\Controllers\Api\StudyController::class, 'create'])->name('api.studies.create');
    Route::put('/api/studies/{id}', [App\Http\Controllers\Api\StudyController::class, 'save'])->whereNumber('id')->name('api.studies.save');
    Route::delete('/api/studies/{id}', [App\Http\Controllers\Api\StudyController::class, 'delete'])->whereNumber('id')->name('api.studies.delete');
    /*
     * Mailings controller routes
     */
    Route::get('/api/mailings', [App\Http\Controllers\Api\MailingController::class, 'all'])->name('api.mailings.all');
    Route::post('/api/mailings', [App\Http\Controllers\Api\MailingController::class, 'create'])->name('api.mailings.create');
    Route::get('/api/mailings/{id}', [App\Http\Controllers\Api\MailingController::class, 'edit'])->whereNumber('id')->name('api.mailings.edit');
    Route::put('/api/mailings/{id}', [App\Http\Controllers\Api\MailingController::class, 'save'])->whereNumber('id')->name('api.mailings.save');
    Route::delete('/api/mailings/{id}', [App\Http\Controllers\Api\MailingController::class, 'delete'])->whereNumber('id')->name('api.mailings.delete');
    Route::delete('/api/mailings/{id}/participants/{participant_id}', [App\Http\Controllers\Api\MailingController::class, 'remove_participant'])->whereNumber(['id', 'participant_id'])->name('api.mailings.remove_participant');
    /*
     * Security controller routes
     */
    Route::get('/api/security', [App\Http\Controllers\Api\SecurityController::class, 'get'])->name('api.security.get');
    /*
     * User controller routes
     */
    Route::get('/api/users', [App\Http\Controllers\Api\UserController::class, 'all'])->name('api.users.all');
    Route::post('/api/users', [App\Http\Controllers\Api\UserController::class, 'create'])->name('api.users.create');
    Route::get('/api/users/{id}', [App\Http\Controllers\Api\UserController::class, 'edit'])->whereNumber('id')->name('api.users.edit');
    Route::put('/api/users/{id}', [App\Http\Controllers\Api\UserController::class, 'save'])->whereNumber('id')->name('api.users.save');
    Route::delete('/api/users/{id}', [App\Http\Controllers\Api\UserController::class, 'delete'])->whereNumber('id')->name('api.users.delete');
    /*
     * Profile controller routes
     */
    Route::get('/api/profile', [App\Http\Controllers\Api\ProfileController::class, 'get'])->name('api.profile.get');
    Route::post('/api/profile', [App\Http\Controllers\Api\ProfileController::class, 'save'])->name('api.profile.save');
    /*
     * Log controller routes
     */
    Route::get('/api/log', [App\Http\Controllers\Api\LogController::class, 'get'])->name('api.log.get');
    /*
     * Setting controller routes
     */
    Route::get('/api/settings', [App\Http\Controllers\Api\SettingController::class, 'all'])->name('api.settings.all');
    Route::put('/api/settings/{id}', [App\Http\Controllers\Api\SettingController::class, 'save'])->whereNumber('id')->name('api.settings.save');
    /*
     * Catch all route to support vue router
     */
    Route::get('{any}', [App\Http\Controllers\HomeController::class, 'admin'])->where('any','.*');
});
