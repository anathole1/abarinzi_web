<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberProfileController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\AboutContentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Models\HeroSlide;
use App\Models\AboutContent;

Route::get('/', function () {
    $heroSlides = HeroSlide::where('is_active', true)
                            ->orderBy('order')
                            ->orderBy('created_at')
                            ->get();

    // Assuming only one row of about content, with ID 1
    $aboutContent = AboutContent::find(1);
    if (!$aboutContent) {
        // Create a default one if it doesn't exist, or handle gracefully
        $aboutContent = new AboutContent([
            // Populate with default values from your AboutContent model or here
            'main_title' => 'About EFOTEC Alumni',
            'main_subtitle' => "We're a dedicated community committed to connection, support, and lifelong learning...",
            'story_title' => 'Our Story',
            'story_paragraph1' => 'Founded with the vision to unite EFOTEC graduates...',
            'story_paragraph2' => 'Over the years, we\'ve launched numerous initiatives...',
            'value_cards' => [
                ['icon_svg_path' => 'M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-4.5A3.375 3.375 0 0 0 12.75 9.75H11.25A3.375 3.375 0 0 0 7.5 13.125V18.75m9 0h-9', 'title' => 'Connection', 'description' => 'Fostering strong bonds and meaningful relationships among all EFOTEC graduates.'],
                ['icon_svg_path' => 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m8.198 0a24.716 24.716 0 0 0-7.734 0m7.734 0a24.733 24.733 0 0 1 3.741 0M6 18.719L6 7.25a6 6 0 0 1 6-6s6 2.686 6 6v11.47Z', 'title' => 'Support', 'description' => 'Providing resources, mentorship, and a supportive network for professional and personal development.'],
                ['icon_svg_path' => 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941', 'title' => 'Growth', 'description' => 'Committed to the continuous learning and advancement of our members and the legacy of EFOTEC.'],
            ],
            'join_title' => 'Become a Member',
            'join_text' => 'Join our active community and benefit from a wealth of resources and connections.',
            'join_button_text' => 'Register Now',
            'stats' => [
                ['value' => '100+', 'label' => 'Successful Events'],
                ['value' => '500+', 'label' => 'Active Members'],
                ['value' => '20+', 'label' => 'Volunteer Leaders'],
                ['value' => '5+', 'label' => 'Years of Community'],
            ],
        ]);
        // $aboutContent->save(); // If you want to save the default if it didn't exist
    }

    // For Contact Us details - assuming these are also somewhat static or managed similarly
    // For simplicity, I'll hardcode them here, but ideally, they'd come from a settings table or AboutContent
    $contactDetails = [
        'title' => 'Contact Us',
        'subtitle' => 'Have questions or want to get involved with the EFOTEC Alumni Association? We\'d love to hear from you.',
        'location_line1' => 'EFOTEC Campus Main Building',
        'location_line2' => 'Kigali, Rwanda',
        'phone' => '+250 7XX XXX XXX',
        'phone_hours' => 'Mon-Fri, 9am-5pm CAT',
        'email' => 'alumni@efotec.rw',
        'email_response_time' => "We'll respond within 2 business days",
        'social_links' => [
            ['platform' => 'Facebook', 'url' => '#', 'icon_svg_path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
            ['platform' => 'Twitter', 'url' => '#', 'icon_svg_path' => 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z'],
            ['platform' => 'LinkedIn', 'url' => '#', 'icon_svg_path' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
        ],
    ];

    return view('welcome', [
        'heroSlides' => $heroSlides,
        'aboutContent' => $aboutContent,
        'contactDetails' => $contactDetails,
    ]);
})->name('welcome');
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
    Route::get('about-content/edit', [AboutContentController::class, 'edit'])
         ->name('about.edit')
         ->middleware('permission:manage about content');
    Route::put('about-content/update', [AboutContentController::class, 'update'])
         ->name('about.update')
         ->middleware('permission:manage about content');

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
    Route::patch('/memberships/{memberProfile}/approve', [App\Http\Controllers\Admin\MembershipController::class, 'approve'])
        ->name('memberships.approve');
    Route::patch('/memberships/{memberProfile}/reject', [App\Http\Controllers\Admin\MembershipController::class, 'reject'])
        ->name('memberships.reject');
    
    //contributions
    Route::resource('contributions', App\Http\Controllers\Admin\ContributionController::class);
    Route::patch('contributions/{contribution}/approve', [App\Http\Controllers\Admin\ContributionController::class, 'approve'])->name('contributions.approve');
    Route::patch('contributions/{contribution}/reject', [App\Http\Controllers\Admin\ContributionController::class, 'reject'])->name('contributions.reject');

     Route::resource('users', App\Http\Controllers\Admin\UserController::class);
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
            ->middleware('can:view,contribution'); // Authorization
    });
});

require __DIR__.'/auth.php';