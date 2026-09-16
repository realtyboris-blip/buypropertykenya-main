<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HouseTourController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AreaGuideController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PodcastController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Models\Inquiry;
use App\Mail\LeadAutoReply;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Blog Routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Property Routes
Route::prefix('properties')->name('properties.')->group(function () {
    Route::get('/', [PropertyController::class, 'index'])->name('index');
    Route::get('/{slug}', [PropertyController::class, 'show'])->name('show');
});

// Lead Routes - FIXED
Route::get('/request-property-info', [LeadController::class, 'create'])->name('lead.form');
Route::post('/lead-submit', [LeadController::class, 'store'])->name('lead.submit');

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// House Tours Routes
Route::prefix('house-tours')->name('house-tours.')->group(function () {
    Route::get('/', [HouseTourController::class, 'index'])->name('index');
    Route::get('/{slug}', [HouseTourController::class, 'show'])->name('show');
});

// Podcasts Routes
Route::prefix('podcasts')->name('podcasts.')->group(function () {
    Route::get('/', [PodcastController::class, 'index'])->name('index');
    Route::get('/{slug}', [PodcastController::class, 'show'])->name('show');
});

// FAQs Routes
Route::prefix('faqs')->name('faqs.')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('index');
});

// Area Guides Routes
Route::prefix('area-guides')->name('area-guides.')->group(function () {
    Route::get('/', [AreaGuideController::class, 'index'])->name('index');
    Route::get('/{slug}', [AreaGuideController::class, 'show'])->name('show');
});

// Project Routes
Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/ongoing', [ProjectController::class, 'ongoing'])->name('ongoing');
    Route::get('/completed', [ProjectController::class, 'completed'])->name('completed');
    Route::get('/off-plan', [ProjectController::class, 'offPlan'])->name('off-plan');
    Route::get('/{slug}', [ProjectController::class, 'show'])->name('show');
});
//Tests 



Route::get('/test-auto-reply', function () {
    // Create a test inquiry
    $inquiry = Inquiry::create([
        'name' => 'Test User',
        'email' => 'awilobest@gmail.com',
        'phone' => '+254712345678',
        'inquiry_type' => 'viewing',
        'message' => 'I am interested in viewing properties in Nairobi.',
        'status' => 'pending',
        'source' => 'test',
    ]);

    try {
        Mail::to($inquiry->email)->send(new LeadAutoReply($inquiry));
        return '✅ Auto-reply email sent successfully! Check your inbox.';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});

Route::get('/test-email', function () {
    try {
        $mailer = config('mail.default');
        $host = config('mail.mailers.' . $mailer . '.host') ?? 'N/A';
        
        echo "Using mailer: $mailer<br>";
        echo "Host: $host<br><br>";
        
        Mail::raw('This is a test email from BuyProperty Kenya using ' . ucfirst($mailer) . '!', function ($message) {
            $message->to('awilobest@gmail.com')
                    ->subject('SendGrid Test - ' . config('mail.default'));
        });
        
        return '✅ Email sent successfully! Check your inbox.';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});

// Authentication Routes
require __DIR__ . '/auth.php';