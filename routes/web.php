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
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\PublicNewsController;
use Illuminate\Support\Facades\Route;

// ============================================
// ROUTES PUBLIQUES (Vitrine)
// ============================================

// Accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Événements publics
Route::get('/events', [PublicEventController::class, 'index'])->name('public.events.index');
Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('public.events.show');
Route::get('/events/{event}/participate', [PublicEventController::class, 'participateForm'])->name('public.events.participate');
Route::post('/events/{event}/participate', [PublicEventController::class, 'participate'])->name('public.events.participate.submit');
Route::get('/events/{event}/participate-form', [PublicEventController::class, 'participateForm'])->name('public.events.participate.form');

// Actualités publiques
Route::get('/actualites', [PublicNewsController::class, 'index'])->name('public.news.index');
Route::get('/actualites/{slug}', [PublicNewsController::class, 'show'])->name('public.news.show');

// Galerie publique

Route::get('/gallery', [PublicGalleryController::class, 'index'])->name('public.gallery.index');
Route::get('/gallery/{gallery}', [PublicGalleryController::class, 'show'])->name('public.gallery.show');
Route::get('/gallery/category/{category}', [PublicGalleryController::class, 'byCategory'])->name('public.gallery.category');
Route::post('/gallery/{gallery}/like', [PublicGalleryController::class, 'like'])->name('gallery.like');


// About (pages statiques)
Route::get('/about', [HomeController::class, 'history'])->name('about');
Route::get('/about/history', [HomeController::class, 'history'])->name('about.history');
Route::get('/about/mission', [HomeController::class, 'mission'])->name('about.mission');
Route::get('/about/vision', [HomeController::class, 'vision'])->name('about.vision');
Route::get('/about/committee', [HomeController::class, 'committee'])->name('about.committee');
Route::get('/about/sections', [HomeController::class, 'sections'])->name('about.sections');

// Pages dynamiques (doit être après les routes statiques)
Route::get('/page/{slug}', [HomeController::class, 'showPage'])->name('page.show');

// Contact
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/send', [HomeController::class, 'sendContact'])->name('contact.send');

// Newsletter
Route::post('/newsletter/subscribe', [HomeController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

// Candidature d'adhésion
Route::get('/membership/apply', [MembershipController::class, 'applyForm'])->name('membership.apply');
Route::post('/membership/apply', [MembershipController::class, 'submitApplication'])->name('membership.submit');
Route::get('/membership/thankyou', [MembershipController::class, 'thankYou'])->name('membership.thankyou');

// ============================================
// ESPACE PERSONNEL (Utilisateur connecté)
// ============================================
Route::middleware(['auth'])->group(function () {
    // Profil
    Route::get('/my/profile', [MyProfileController::class, 'profile'])->name('my.profile');
    Route::post('/my/profile/update', [MyProfileController::class, 'updateProfile'])->name('my.profile.update');
    Route::post('/my/password/update', [MyProfileController::class, 'updatePassword'])->name('my.password.update');

    // Carte de membre
    Route::get('/my/card', [MyProfileController::class, 'card'])->name('my.card');
    
    // Contributions
    Route::get('/my/contributions', [MyProfileController::class, 'contributions'])->name('my.contributions');
    
    // Transactions
    Route::get('/my/transactions', [MyProfileController::class, 'transactions'])->name('my.transactions');
    
    // Événements
    Route::get('/my/events', [MyProfileController::class, 'events'])->name('my.events');
});

// ============================================
// ROUTES ADMINISTRATION
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== GESTION DES MEMBRES =====
    Route::resource('members', MemberController::class);
    Route::post('/members/{member}/approve', [MemberController::class, 'approve'])->name('members.approve');
    Route::post('/members/{member}/reject', [MemberController::class, 'reject'])->name('members.reject');
    Route::get('/members/{member}/card', [MemberController::class, 'generateCard'])->name('members.card');
    Route::get('/members/export/all', [MemberController::class, 'export'])->name('members.export');
    
    // ===== GESTION DES COMPTES UTILISATEURS =====
    Route::post('/members/{member}/create-user', [MemberController::class, 'createUser'])->name('members.create-user');
    Route::post('/members/{member}/reset-password', [MemberController::class, 'resetPassword'])->name('members.reset-password');

    // ===== GESTION FINANCIÈRE =====
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/dashboard', [FinancialController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [FinancialController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/create', [FinancialController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [FinancialController::class, 'store'])->name('transactions.store');
        Route::get('/transactions/{transaction}/edit', [FinancialController::class, 'edit'])->name('transactions.edit');
        Route::put('/transactions/{transaction}', [FinancialController::class, 'update'])->name('transactions.update');
        Route::delete('/transactions/{transaction}', [FinancialController::class, 'destroy'])->name('transactions.destroy');
        Route::post('/transactions/{transaction}/approve', [FinancialController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [FinancialController::class, 'reject'])->name('transactions.reject');
        Route::get('/report', [FinancialController::class, 'report'])->name('report');
        Route::get('/export/pdf', [FinancialController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [FinancialController::class, 'exportExcel'])->name('export.excel');
        Route::get('/contributions', [FinancialController::class, 'contributions'])->name('contributions');
        Route::post('/contributions/record', [FinancialController::class, 'recordContribution'])->name('contributions.record');
    });

    // ===== GESTION DES ÉVÉNEMENTS =====
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/add-participant', [EventController::class, 'addParticipant'])->name('events.add-participant');
    Route::post('/events/{event}/update-participation', [EventController::class, 'updateParticipation'])->name('events.update-participation');
    Route::get('/events/calendar/view', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('/events/{event}/report', [EventController::class, 'report'])->name('events.report');

    // ===== GALERIE =====
    Route::resource('gallery', GalleryController::class);
    Route::post('/gallery/{gallery}/like', [GalleryController::class, 'like'])->name('gallery.like');

    // ===== DOCUMENTS =====
    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // ===== BUREAU EXÉCUTIF =====
    Route::resource('committee', CommitteeController::class);
    Route::post('/committee/{committee}/end', [CommitteeController::class, 'endMandate'])->name('committee.end');

    // ===== RAPPORTS =====
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/members', [ReportController::class, 'members'])->name('members');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/events', [ReportController::class, 'events'])->name('events');
        Route::get('/contributions', [ReportController::class, 'contributions'])->name('contributions');
    });

    // ===== GESTION DU CONTENU DU SITE =====
    
    // Pages
    Route::resource('pages', PageController::class);
    Route::post('/pages/{page}/publish', [PageController::class, 'publish'])->name('pages.publish');
    Route::post('/pages/{page}/unpublish', [PageController::class, 'unpublish'])->name('pages.unpublish');
    
    // Carousel
    Route::resource('carousels', CarouselController::class);
    Route::post('/carousels/{carousel}/toggle', [CarouselController::class, 'toggle'])->name('carousels.toggle');
    
    // Témoignages
    Route::resource('testimonials', TestimonialController::class);
    Route::post('/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('/testimonials/{testimonial}/toggle', [TestimonialController::class, 'toggle'])->name('testimonials.toggle');
    
    // Actualités (News)
    Route::resource('news', NewsController::class);
    Route::post('/news/{news}/publish', [NewsController::class, 'publish'])->name('news.publish');
    Route::get('/news/{news}/show', [NewsController::class, 'show'])->name('news.show');

    // ===== PARAMÈTRES =====
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/cache/clear', [SettingController::class, 'clearCache'])->name('settings.cache.clear');
});

Route::resource('documents', DocumentController::class);
Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
Route::post('/documents/{document}/toggle-public', [DocumentController::class, 'togglePublic'])->name('documents.toggle-public');

// ============================================
// ROUTES D'AUTHENTIFICATION
// ============================================
require __DIR__.'/auth.php';