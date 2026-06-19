<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PublicGalleryController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Routes publiques (vitrine)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Événements publics
Route::get('/events', [PublicEventController::class, 'index'])->name('public.events.index');
Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('public.events.show');
Route::get('/events/{event}/participate', [PublicEventController::class, 'participateForm'])->name('public.events.participate');
Route::post('/events/{event}/participate', [PublicEventController::class, 'participate'])->name('public.events.participate.submit');
Route::get('/events/{event}/participate-form', [PublicEventController::class, 'participateForm'])->name('public.events.participate.form');

Route::get('/events/{event}/participate-form', [PublicEventController::class, 'participateForm'])
    ->name('public.events.participate.form');

Route::post('/events/{event}/participate', [PublicEventController::class, 'participate'])
    ->name('public.events.participate.submit');

// Galerie publique
Route::get('/gallery', [PublicGalleryController::class, 'index'])->name('public.gallery.index');

Route::get('/about/history', [HomeController::class, 'history'])->name('about.history');
Route::get('/about/mission', [HomeController::class, 'mission'])->name('about.mission');
Route::get('/about/vision', [HomeController::class, 'vision'])->name('about.vision');
Route::get('/about/committee', [HomeController::class, 'committee'])->name('about.committee');
Route::get('/about/sections', [HomeController::class, 'sections'])->name('about.sections');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/send', [HomeController::class, 'sendContact'])->name('contact.send');
Route::post('/newsletter/subscribe', [HomeController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

// Candidature d'adhésion
Route::get('/membership/apply', [MembershipController::class, 'applyForm'])->name('membership.apply');
Route::post('/membership/apply', [MembershipController::class, 'submitApplication'])->name('membership.submit');
Route::get('/membership/thankyou', [MembershipController::class, 'thankYou'])->name('membership.thankyou');

// Routes Admin
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Gestion des membres
    Route::resource('members', MemberController::class);
    Route::post('/members/{member}/approve', [MemberController::class, 'approve'])->name('members.approve');
    Route::post('/members/{member}/reject', [MemberController::class, 'reject'])->name('members.reject');
    Route::get('/members/{member}/card', [MemberController::class, 'generateCard'])->name('members.card');
    Route::get('/members/export/all', [MemberController::class, 'export'])->name('members.export');
    
    // Gestion financière
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/dashboard', [FinancialController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [FinancialController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/create', [FinancialController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [FinancialController::class, 'store'])->name('transactions.store');
        Route::post('/transactions/{transaction}/approve', [FinancialController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [FinancialController::class, 'reject'])->name('transactions.reject');
        Route::get('/report', [FinancialController::class, 'report'])->name('report');
        Route::get('/export/pdf', [FinancialController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [FinancialController::class, 'exportExcel'])->name('export.excel');
        Route::get('/contributions', [FinancialController::class, 'contributions'])->name('contributions');
        Route::post('/contributions/record', [FinancialController::class, 'recordContribution'])->name('contributions.record');
    });
    
    // Gestion des événements
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/add-participant', [EventController::class, 'addParticipant'])->name('events.add-participant');
    Route::post('/events/{event}/update-participation', [EventController::class, 'updateParticipation'])->name('events.update-participation');
    Route::get('/events/calendar/view', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('/events/{event}/report', [EventController::class, 'report'])->name('events.report');
    
    // Galerie
    Route::resource('gallery', GalleryController::class);
    Route::post('/gallery/{gallery}/like', [GalleryController::class, 'like'])->name('gallery.like');
    
    // Documents
    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    
    // Bureau exécutif
    Route::resource('committee', CommitteeController::class);
    Route::post('/committee/{committee}/end', [CommitteeController::class, 'endMandate'])->name('committee.end');
    
    // Rapports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/members', [ReportController::class, 'members'])->name('members');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/events', [ReportController::class, 'events'])->name('events');
        Route::get('/contributions', [ReportController::class, 'contributions'])->name('contributions');
    });
});

require __DIR__.'/auth.php';