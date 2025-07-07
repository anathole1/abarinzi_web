<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // Keep for admin search route if still there

// Public Page Controllers
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController; // For form submission
use App\Http\Controllers\NewsletterController; // If you have this

// Authenticated User Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\Member\ContributionController as MemberContributionController; // Alias for clarity

// Admin Area Controllers
use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\Admin\ContributionController as AdminContributionController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\MemberCategoryController as AdminMemberCategoryController;
use App\Http\Controllers\Member\LoanApplicationController as MemberLoanApplicationController;
use App\Http\Controllers\Member\LoanRepaymentController as MemberLoanRepaymentController;

// Content Management Admin Controllers
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\AboutContentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CoreObjectiveItemController;
use App\Http\Controllers\Admin\VisionItemController;

use App\Models\User; // For admin search route closure if kept

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Publicly Accessible Pages
Route::get('/', [PageController::class, 'welcome'])->name('welcome'); // Changed from 'welcome' to 'home' for convention
Route::get('/about/our-work-and-vision', [PageController::class, 'aboutOurWorkAndVision'])->name('about.work-vision');
Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::post('/newsletter-subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');


// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member Profile Completion
    Route::get('/complete-profile', [MemberProfileController::class, 'create'])->name('member-profile.create');
    Route::post('/member-profile', [MemberProfileController::class, 'store'])->name('member-profile.store');

    // Member-specific features (require approved profile)
    Route::middleware(['ensure.profile.approved'])->group(function () {
        Route::get('/contributions', [MemberContributionController::class, 'index'])->name('member.contributions.index');
        Route::get('/contributions/create', [MemberContributionController::class, 'create'])->name('member.contributions.create');
        Route::post('/contributions', [MemberContributionController::class, 'store'])->name('member.contributions.store');
        Route::get('/contributions/{contribution}', [MemberContributionController::class, 'show'])
            ->name('member.contributions.show')
            ->middleware('can:view,contribution');
        Route::resource('my-loans', MemberLoanApplicationController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->names([ // Custom names to avoid conflict with admin loan routes if any
            'index' => 'member.loans.index',
            'create' => 'member.loans.create',
            'store' => 'member.loans.store',
            'show' => 'member.loans.show',
        ]);
        Route::get('my-loans/{loan}/repayments/create', [MemberLoanRepaymentController::class, 'create'])->name('member.loan_repayments.create');
        Route::post('my-loans/{loan}/repayments', [MemberLoanRepaymentController::class, 'store'])->name('member.loan_repayments.store');
    });
});


// Admin Area Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Admin-Only Section (Role: admin)
    Route::middleware(['role:admin'])->group(function() {
        Route::get('/dashboard', function () { // Simple admin dashboard
            // You can create an AdminDashboardController later if this gets complex
            $viewData = [
                'totalUsers' => User::count(),
                'pendingMembershipsCount' => \App\Models\MemberProfile::where('status', 'pending')->count(),
                // Add more admin specific stats
            ];
            return view('admin.dashboard', $viewData); // Assuming you have/create this view
        })->name('dashboard');

        // User Management
        Route::resource('users', AdminUserController::class);
        Route::get('users/{user}/roles', [AdminUserController::class, 'showRoles'])->name('users.roles');
        Route::post('users/{user}/roles', [AdminUserController::class, 'assignRoles'])->name('users.assignRoles');
        // ... other user permission routes ...

        // Role & Permission Management
        Route::resource('roles', AdminRoleController::class);
        Route::get('roles/{role}/permissions', [AdminRoleController::class, 'showPermissions'])->name('roles.permissions');
        Route::post('roles/{role}/permissions', [AdminRoleController::class, 'assignPermissions'])->name('roles.assignPermissions');
        Route::resource('permissions', AdminPermissionController::class)->only(['index', 'create', 'store', 'destroy']);

        // Membership Management
        Route::get('/memberships', [AdminMembershipController::class, 'index'])->name('memberships.index');
        Route::get('/memberships/{memberProfile}', [AdminMembershipController::class, 'show'])->name('memberships.show');
        Route::patch('/memberships/{memberProfile}/approve', [AdminMembershipController::class, 'approve'])->name('memberships.approve');
        Route::patch('/memberships/{memberProfile}/reject', [AdminMembershipController::class, 'reject'])->name('memberships.reject');
        Route::resource('member-categories', AdminMemberCategoryController::class)->except(['show']);


        // Contribution Management (Admin)
        Route::resource('contributions', AdminContributionController::class);
        Route::patch('contributions/{contribution}/approve', [AdminContributionController::class, 'approve'])->name('contributions.approve');
        Route::patch('contributions/{contribution}/reject', [AdminContributionController::class, 'reject'])->name('contributions.reject');

        // Loan Management (Admin)
        Route::resource('loans', AdminLoanController::class);

        // AJAX Search Routes (Admin)
        Route::get('/search-members', function (Request $request) { /* ... your search closure ... */ })->name('members.search'); // Consider moving to a controller
        Route::get('/get-member-category-amounts/{user}', function (User $user) { /* ... your category amounts closure ... */ })->name('members.category-amounts'); // Consider moving
    });


    // Content Management Section (Role: admin OR author)
    Route::middleware(['role:admin|author'])->prefix('content')->name('content.')->group(function () {
        Route::resource('hero-slides', HeroSlideController::class);
        Route::get('about-main/edit', [AboutContentController::class, 'edit'])->name('about.edit');
        Route::put('about-main/update', [AboutContentController::class, 'update'])->name('about.update');
        Route::resource('objectives', CoreObjectiveItemController::class)->except(['show']);
        Route::resource('vision-items', VisionItemController::class)->except(['show']);
        Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contacts.index');
        Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contacts.show');
        Route::patch('contact-messages/{contactMessage}/toggle-read', [ContactMessageController::class, 'toggleRead'])->name('contacts.toggleRead');
        Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contacts.destroy')->middleware('permission:delete contact messages');
    });
});


// Breeze Auth Routes
require __DIR__.'/auth.php';