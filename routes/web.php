<?php


use App\Http\Controllers\secondParentVerification;
use App\Http\Controllers\VerificationController;
use App\Livewire\Admin\Season;
use App\Livewire\Createcarpool;
use App\Livewire\Admin\MatchTraining;
use App\Livewire\Home;
use App\Livewire\ClothingSizes;
use App\Livewire\OverviewClothingSizes;
use App\Livewire\SpelersLijst;
use App\Livewire\Trainer\UsersRegistration;
use App\Livewire\Kalender;
use App\Livewire\ContactForm;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class)->name('home');
Route::get('/kalender', Kalender::class)->name('kalender');
Route::get('/galerij', \App\Livewire\Photo::class)->name('photo');
Route::get('/contact', ContactForm::class)->name('contact-form');

// User verification
Route::get('/email/verificatie', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verificatie/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verificatie/melding', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Routes for inactive account
Route::middleware(['auth'])->group(function () {
Route::get('/dashboard', Home::class);})->name('dashboard');

// Routes for normal users (not admin)
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/carpool', \App\Livewire\Carpool::class)->name('carpool');
    Route::get('/afwezigheid', \App\Livewire\RegisterAbsence::class)->name('attendance');
    Route::get('/kinderen', \App\Livewire\Kinderen::class)->name('kinderen');
    Route::get('/beurtrol', \App\Livewire\Beurtrol::class)->name("beurtrol");
});

// Routes for administrators
Route::middleware(['auth', 'admin', 'active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('activiteiten', MatchTraining::class)->name('training-match');
    Route::get('spelers', SpelersLijst::class)->name('spelersOverzicht');
    Route::get('registraties', UsersRegistration::class)->name('users.registration');
    Route::get('seizoen', Season::class)->name('season');
    Route::get('kledij', ClothingSizes::class)->name('clothing');
});
