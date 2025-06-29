<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberProfileController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\AboutContentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CoreObjectiveItemController;
use App\Http\Controllers\Admin\VisionItemController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\MemberCategoryController;
use App\Models\HeroSlide;
use App\Models\AboutContent;
use App\Models\CoreObjectiveItem;
use App\Models\VisionItem;


Route::get('/', function () {
    $heroSlides = HeroSlide::where('is_active', true)
                            ->orderBy('order')
                            ->orderBy('created_at') // Secondary sort for consistent ordering if 'order' is same
                            ->get();

    // Get the main "About Us" content (titles, intro, mission, etc.)
    // The getContent() static method in AboutContent model handles fetching or creating the default single record.
    $aboutContent = AboutContent::getContent();

    // For Contact Us details (still assuming this is somewhat static or part of settings)
    // In a more complex setup, these could also come from a dedicated "SiteSettings" model
    $contactDetails = [
        'title' => 'Contact Us',
        'subtitle' => 'Have questions or want to get involved with the Abarinzi family? We\'d love to hear from you.',
        'location_line1' => $aboutContent->contact_location_line1 ?? 'Abarinzi Family Main Building', // Example: could move contact details to AboutContent
        'location_line2' => $aboutContent->contact_location_line2 ?? 'Kigali, Rwanda',
        'phone' => $aboutContent->contact_phone ?? '+250 7XX XXX XXX',
        'phone_hours' => $aboutContent->contact_phone_hours ?? 'Mon-Fri, 9am-5pm CAT',
        'email' => $aboutContent->contact_email ?? 'info@abarinzifamily.rw',
        'email_response_time' => $aboutContent->contact_email_response_time ?? "We'll respond within 2 business days",
        'social_links' => $aboutContent->social_links ?? [ // Assuming social_links might be a JSON field in AboutContent
            ['platform' => 'Facebook', 'url' => '#', 'icon_svg_path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
            ['platform' => 'Twitter', 'url' => '#', 'icon_svg_path' => 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z'],
        ],
    ];


    return view('welcome', [
        'heroSlides' => $heroSlides,
        'aboutContent' => $aboutContent, // This now contains main texts, mission summary, section titles
        'contactDetails' => $contactDetails, // For the contact info panel and footer
    ]);
})->name('welcome');

Route::get('/about/our-work-and-vision', function () {
    $aboutContent = AboutContent::getContent(); // Get the main text fields
    $coreObjectives = CoreObjectiveItem::orderBy('order')->get();
    $visionItems = VisionItem::orderBy('order')->get();

    return view('about-our-work', compact('aboutContent', 'coreObjectives', 'visionItems'));
})->name('about.work-vision');

Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');

// Group for Content Management
Route::middleware(['auth', 'verified','role:author']) // Basic auth and email verification
    ->prefix('admin/content')
    ->name('admin.content.')
    ->group(function () {

    // Hero Slides Management
    // Apply 'manage hero slides' permission to all resource routes for HeroSlideController
    Route::resource('hero-slides', HeroSlideController::class)
         ->middleware('permission:manage hero slides'); // Apply to the whole resource

    // About Us Content Management
    // Apply 'manage about content' to these specific routes
      // Main About Content Texts
        Route::get('about-main/edit', [AboutContentController::class, 'edit'])->name('about.edit')->middleware('permission:manage about content');
        Route::put('about-main/update', [AboutContentController::class, 'update'])->name('about.update')->middleware('permission:manage about content');

        // Core Objective Items CRUD
        Route::resource('objectives', CoreObjectiveItemController::class)->except(['show'])->middleware('permission:manage about content');
        // Vision Items CRUD
        Route::resource('vision-items', VisionItemController::class)->except(['show'])->middleware('permission:manage about content');
    // Route::get('about-content/edit', [AboutContentController::class, 'edit'])
    //      ->name('about.edit')
    //      ->middleware('permission:manage about content');
    // Route::put('about-content/update', [AboutContentController::class, 'update'])
    //      ->name('about.update')
    //      ->middleware('permission:manage about content');

    // Contact Message Management
    Route::get('contact-messages', [ContactMessageController::class, 'index'])
         ->name('contacts.index')
         ->middleware('permission:view contact messages');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])
         ->name('contacts.show')
         ->middleware('permission:view contact messages');
    Route::patch('contact-messages/{contactMessage}/toggle-read', [ContactMessageController::class, 'toggleRead'])
         ->name('contacts.toggleRead')
         ->middleware('permission:view contact messages'); // Or a more specific 'manage contact messages'
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])
         ->name('contacts.destroy')
         ->middleware('permission:delete contact messages');
});
//admin and pending member
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole(['admin|author'],)) {
        return view('dashboard'); // Or an admin specific one
    }
    if ($user->memberProfile) {
        if ($user->memberProfile->status === 'pending') {
            return view('dashboard-pending');
        } elseif ($user->memberProfile->status === 'approved') {
             // Fetch contribution summary for approved members
            $viewData['totalApprovedContributions'] = $user->contributions()
                ->where('status', 'approved')
                ->where('type','regular')
                ->sum('amount');
            $statusCounts = $user->contributions()
                ->groupBy('status')
                ->select('status', DB::raw('count(*) as count'))->where('type','regular')
                ->pluck('count', 'status');
            $viewData['pendingContributionsCount'] = $statusCounts->get('pending', 0);
            $viewData['approvedContributionsCount'] = $statusCounts->get('approved', 0);
            

            return view('dashboard',$viewData);
        } elseif ($user->memberProfile->status === 'rejected') {
            return view('dashboard'); // dashboard.blade.php handles rejected message
        }
    }
    // Fallback if member has no profile yet (should be redirected by RegisteredUserController or middleware)
    return redirect()->route('member-profile.create');
})->middleware(['auth', 'verified'])->name('dashboard');

//admin user only

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { // Optional: specific admin dashboard
        return view('admin.dashboard'); // You'd create this view
    })->name('dashboard');

    //memberships
        Route::get('/memberships', [App\Http\Controllers\Admin\MembershipController::class, 'index'])
        ->name('memberships.index');
    Route::get('/memberships/{memberProfile}', [App\Http\Controllers\Admin\MembershipController::class, 'show']) // ADD THIS
        ->name('memberships.show');
    Route::patch('/memberships/{memberProfile}/approve', [App\Http\Controllers\Admin\MembershipController::class, 'approve'])
        ->name('memberships.approve');
    Route::patch('/memberships/{memberProfile}/reject', [App\Http\Controllers\Admin\MembershipController::class, 'reject'])
        ->name('memberships.reject');
    // Route::get('/memberships', [App\Http\Controllers\Admin\MembershipController::class, 'index'])
    //     ->name('memberships.index');
    // Route::patch('/memberships/{memberProfile}/approve', [App\Http\Controllers\Admin\MembershipController::class, 'approve'])
    //     ->name('memberships.approve');
    // Route::patch('/memberships/{memberProfile}/reject', [App\Http\Controllers\Admin\MembershipController::class, 'reject'])
    //     ->name('memberships.reject');
    
    //contributions
    Route::resource('contributions', App\Http\Controllers\Admin\ContributionController::class);
    Route::patch('contributions/{contribution}/approve', [App\Http\Controllers\Admin\ContributionController::class, 'approve'])->name('contributions.approve');
    Route::patch('contributions/{contribution}/reject', [App\Http\Controllers\Admin\ContributionController::class, 'reject'])->name('contributions.reject');
    Route::resource('loans', LoanController::class)->middleware('can:manage loans');
    
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('member-categories', MemberCategoryController::class)->except(['show']);
    // Additional routes for assigning roles/permissions to users
    Route::get('users/{user}/roles', [App\Http\Controllers\Admin\UserController::class, 'showRoles'])->name('users.roles');
    Route::post('users/{user}/roles', [App\Http\Controllers\Admin\UserController::class, 'assignRoles'])->name('users.assignRoles');
    Route::get('users/{user}/permissions', [App\Http\Controllers\Admin\UserController::class, 'showPermissions'])->name('users.permissions'); // If direct permission assignment is needed
    Route::post('users/{user}/permissions', [App\Http\Controllers\Admin\UserController::class, 'assignPermissions'])->name('users.assignPermissions'); // If direct permission assignment is needed


    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    // Additional routes for assigning permissions to roles
    Route::get('roles/{role}/permissions', [App\Http\Controllers\Admin\RoleController::class, 'showPermissions'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [App\Http\Controllers\Admin\RoleController::class, 'assignPermissions'])->name('roles.assignPermissions');


    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class)->only(['index', 'create', 'store', 'destroy']);
    
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member Profile Routes
    Route::get('/complete-profile', [MemberProfileController::class, 'create'])->name('member-profile.create');
    Route::post('/member-profile', [MemberProfileController::class, 'store'])->name('member-profile.store');

    // Routes that REQUIRE an approved profile
    Route::middleware(['ensure.profile.approved'])->group(function () {
        // Member Contributions
        Route::get('/contributions', [App\Http\Controllers\Member\ContributionController::class, 'index'])
            ->name('member.contributions.index');
        Route::get('/contributions/create', [App\Http\Controllers\Member\ContributionController::class, 'create'])
            ->name('member.contributions.create');
        Route::post('/contributions', [App\Http\Controllers\Member\ContributionController::class, 'store'])
            ->name('member.contributions.store');
        Route::get('/contributions/{contribution}', [App\Http\Controllers\Member\ContributionController::class, 'show'])
            ->name('member.contributions.show')
            ->middleware('can:view own contributions'); // Authorization
    });
});

require __DIR__.'/auth.php';