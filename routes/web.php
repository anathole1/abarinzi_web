<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER IMPORTS ---
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Member\ContributionController as MemberContributionController;
use App\Http\Controllers\Member\LoanApplicationController as MemberLoanApplicationController;
use App\Http\Controllers\Member\LoanRepaymentController as MemberLoanRepaymentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\Admin\ProfileUpdateController as AdminProfileUpdateController;
use App\Http\Controllers\Admin\MemberCategoryController as AdminMemberCategoryController;
use App\Http\Controllers\Admin\ContributionController as AdminContributionController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\LoanRepaymentController as AdminLoanRepaymentController;
use App\Http\Controllers\Admin\OfficeController as AdminOfficeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\AboutContentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CoreObjectiveItemController;
use App\Http\Controllers\Admin\VisionItemController;
use App\Http\Controllers\Admin\AjaxController as AdminAjaxController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Organized by accessibility: Public, Authenticated, and Admin areas.
| All logic is now handled by dedicated controllers.
|
*/

// --- 1. PUBLIC ROUTES ---
Route::get('/', [PageController::class, 'welcome'])->name('welcome');
Route::get('/about/our-work-and-vision', [PageController::class, 'aboutOurWorkAndVision'])->name('about.work-vision');
Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');


// --- 2. AUTHENTICATED USER ROUTES (Applies to ALL logged-in users) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Default Breeze Profile (Password, Delete Account)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes specifically for users with the 'member' role
    Route::middleware(['role:member'])->group(function () {
        Route::get('/complete-profile', [MemberProfileController::class, 'create'])->name('member-profile.create');
        Route::post('/member-profile', [MemberProfileController::class, 'store'])->name('member-profile.store');

        // Routes for APPROVED members
        Route::middleware(['ensure.profile.approved'])->group(function () {
            Route::get('/my-profile/edit', [MemberProfileController::class, 'edit'])->name('member-profile.edit');
            Route::put('/my-profile/update', [MemberProfileController::class, 'update'])->name('member-profile.update');
            Route::resource('contributions', MemberContributionController::class)->only(['index', 'create', 'store', 'show'])->names('member.contributions');
            Route::resource('my-loans', MemberLoanApplicationController::class)->only(['index', 'create', 'store', 'show'])->names('member.loans');
            Route::get('my-loans/{loan}/repayments/create', [MemberLoanRepaymentController::class, 'create'])->name('member.loan_repayments.create');
            Route::post('my-loans/{loan}/repayments', [MemberLoanRepaymentController::class, 'store'])->name('member.loan_repayments.store');
        });
    });
});


// --- 3. ADMIN AREA ROUTES ---
// This is the main "gatekeeper" for the entire /admin section.
Route::middleware(['auth', 'verified', 'role:admin|approval|author'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Main Admin Dashboard - Accessible to all roles in this group
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // -- Content Management (Author & Admin) --
    Route::middleware(['permission:manage website content'])->prefix('content')->name('content.')->group(function () {
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

    // -- Approval & Financial Tasks (Approval & Admin) --
    Route::middleware(['permission:approve memberships|approve contributions|approve loans|confirm loan repayments'])->group(function() {
        Route::get('/memberships', [AdminMembershipController::class, 'index'])->name('memberships.index');
        Route::get('/memberships/{memberProfile}', [AdminMembershipController::class, 'show'])->name('memberships.show');
        Route::patch('/memberships/{memberProfile}/approve', [AdminMembershipController::class, 'approve'])->name('memberships.approve');
        Route::patch('/memberships/{memberProfile}/reject', [AdminMembershipController::class, 'reject'])->name('memberships.reject');
        Route::get('profile-updates', [AdminProfileUpdateController::class, 'index'])->name('profile-updates.index');
        Route::get('profile-updates/{profileUpdate}', [AdminProfileUpdateController::class, 'show'])->name('profile-updates.show');
        Route::patch('profile-updates/{profileUpdate}/approve', [AdminProfileUpdateController::class, 'approve'])->name('profile-updates.approve');
        Route::patch('profile-updates/{profileUpdate}/reject', [AdminProfileUpdateController::class, 'reject'])->name('profile-updates.reject');
        Route::resource('contributions', AdminContributionController::class);
        Route::resource('loans', AdminLoanController::class);
        Route::get('loan-repayments', [AdminLoanRepaymentController::class, 'index'])->name('loan-repayments.index');
        Route::get('loan-repayments/{loanRepayment}', [AdminLoanRepaymentController::class, 'show'])->name('loan-repayments.show');
        Route::patch('loan-repayments/{loanRepayment}/confirm', [AdminLoanRepaymentController::class, 'confirm'])->name('loan-repayments.confirm');
        Route::patch('loan-repayments/{loanRepayment}/fail', [AdminLoanRepaymentController::class, 'fail'])->name('loan-repayments.fail');
    });

    // -- Full Admin-Only Tasks (System Settings, Users, Reports) --
    Route::middleware(['permission:manage roles and permissions'])->group(function() {
        Route::resource('users', AdminUserController::class);
        Route::get('users/{user}/roles', [AdminUserController::class, 'showRoles'])->name('users.roles');
        Route::post('users/{user}/roles', [AdminUserController::class, 'assignRoles'])->name('users.assignRoles');
        Route::resource('roles', AdminRoleController::class);
        Route::get('roles/{role}/permissions', [AdminRoleController::class, 'showPermissions'])->name('roles.permissions');
        Route::post('roles/{role}/permissions', [AdminRoleController::class, 'assignPermissions'])->name('roles.assignPermissions');
        Route::resource('permissions', AdminPermissionController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::resource('member-categories', AdminMemberCategoryController::class)->except(['show']);
        Route::resource('offices', AdminOfficeController::class);
        Route::get('/reports/members', [AdminReportController::class, 'membersReport'])->name('reports.members');
        Route::get('/reports/members/pdf', [AdminReportController::class, 'exportMembersPdf'])->name('reports.members.pdf');
        Route::get('/reports/members/excel', [AdminReportController::class, 'exportMembersExcel'])->name('reports.members.excel');
        Route::get('/reports/contributions', [AdminReportController::class, 'contributionsReport'])->name('reports.contributions');
        Route::get('/reports/contributions/pdf', [AdminReportController::class, 'exportContributionsPdf'])->name('reports.contributions.pdf');
        Route::get('/reports/contributions/excel', [AdminReportController::class, 'exportContributionsExcel'])->name('reports.contributions.excel');
        Route::get('/reports/loans', [AdminReportController::class, 'loansReport'])->name('reports.loans');
        Route::get('/reports/loans/pdf', [AdminReportController::class, 'exportLoansPdf'])->name('reports.loans.pdf');
        Route::get('/reports/loans/excel', [AdminReportController::class, 'exportLoansExcel'])->name('reports.loans.excel');
    });

    // -- AJAX Routes (accessible by any role in the main admin group) --
    Route::get('/search-members', [AdminAjaxController::class, 'searchMembers'])->name('members.search');
    Route::get('/get-member-category-amounts/{user}', [AdminAjaxController::class, 'getMemberCategoryAmounts'])->name('members.category-amounts');
});

// Breeze Auth Routes
require __DIR__.'/auth.php';