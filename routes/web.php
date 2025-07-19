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
use App\Http\Controllers\Admin\LoanRepaymentController as AdminLoanRepaymentController;
use App\Http\Controllers\Admin\ReportController;

// Content Management Admin Controllers
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\AboutContentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CoreObjectiveItemController;
use App\Http\Controllers\Admin\VisionItemController;

//Notification
use App\Http\Controllers\NotificationController;
//update profile
use App\Http\Controllers\Admin\ProfileUpdateController;
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
//Route::post('/newsletter-subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');


// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member Profile Completion
    Route::get('/complete-profile', [MemberProfileController::class, 'create'])->name('member-profile.create');
    Route::post('/member-profile', [MemberProfileController::class, 'store'])->name('member-profile.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

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

         // Member Profile Update Routes
        Route::get('/my-profile/edit', [MemberProfileController::class, 'edit'])->name('member-profile.edit');
        Route::put('/my-profile/update', [MemberProfileController::class, 'update'])->name('member-profile.update');
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

        //profile approval
        Route::get('profile-updates', [ProfileUpdateController::class, 'index'])->name('profile-updates.index');
        Route::get('profile-updates/{profileUpdate}', [ProfileUpdateController::class, 'show'])->name('profile-updates.show');
        Route::patch('profile-updates/{profileUpdate}/approve', [ProfileUpdateController::class, 'approve'])->name('profile-updates.approve');
        Route::patch('profile-updates/{profileUpdate}/reject', [ProfileUpdateController::class, 'reject'])->name('profile-updates.reject');

        // AJAX Search Route for Tom Select
    Route::get('/search-members', function (Request $request) {
        $searchTerm = $request->input('q', '');
        if (empty($searchTerm)) {
            return response()->json(['items' => []]);
        }

        $query = User::query()
            ->whereHas('memberProfile', fn ($q) => $q->where('status', 'approved'))
            ->orderBy('name');

        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('email', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('memberProfile', function ($mpQuery) use ($searchTerm) {
                  $mpQuery->where('accountNo', 'LIKE', "%{$searchTerm}%");
              });
        });

        $users = $query->limit(20)->get(['id', 'name', 'email']);

        $formattedUsers = $users->map(function ($user) {
            return ['id' => $user->id, 'text' => "{$user->name} ({$user->email})"];
        });

        return response()->json(['items' => $formattedUsers]);
    })->name('members.search');

    // AJAX Route to get category amounts for a selected member
    Route::get('/get-member-category-amounts/{user}', function (User $user) {
        if ($user->memberProfile && $user->memberProfile->memberCategory) {
            return response()->json([
                'monthly' => $user->memberProfile->memberCategory->monthly_contribution,
                'social' => $user->memberProfile->memberCategory->social_monthly_contribution,
            ]);
        }
        return response()->json(null, 404);
    })->name('members.category-amounts');
        // Loan Management (Admin)
        Route::resource('loans', AdminLoanController::class);

        // AJAX Search Routes (Admin)
        Route::get('/search-members', function (Request $request) { /* ... your search closure ... */ })->name('members.search'); // Consider moving to a controller
        Route::get('/get-member-category-amounts/{user}', function (User $user) { /* ... your category amounts closure ... */ })->name('members.category-amounts'); // Consider moving

        Route::get('loan-repayments', [AdminLoanRepaymentController::class, 'index'])->name('loan-repayments.index');
        Route::get('loan-repayments/{loanRepayment}', [AdminLoanRepaymentController::class, 'show'])->name('loan-repayments.show');
        Route::patch('loan-repayments/{loanRepayment}/confirm', [AdminLoanRepaymentController::class, 'confirm'])->name('loan-repayments.confirm');
        Route::patch('loan-repayments/{loanRepayment}/fail', [AdminLoanRepaymentController::class, 'fail'])->name('loan-repayments.fail');
        // Optional: Routes for admin to edit/delete repayment records
        // Route::get('loan-repayments/{loanRepayment}/edit', [AdminLoanRepaymentController::class, 'edit'])->name('loan-repayments.edit');
        // Route::put('loan-repayments/{loanRepayment}', [AdminLoanRepaymentController::class, 'update'])->name('loan-repayments.update');
        // Route::delete('loan-repayments/{loanRepayment}', [AdminLoanRepaymentController::class, 'destroy'])->name('loan-repayments.destroy');

        Route::get('/reports/members', [ReportController::class, 'membersReport'])->name('reports.members');
        Route::get('/reports/members/pdf', [ReportController::class, 'exportMembersPdf'])->name('reports.members.pdf'); // New
        Route::get('/reports/members/excel', [ReportController::class, 'exportMembersExcel'])->name('reports.members.excel'); // New
    
        // -- ADD THESE ROUTES FOR CONTRIBUTIONS --
        Route::get('/reports/contributions', [ReportController::class, 'contributionsReport'])->name('reports.contributions');
        Route::get('/reports/contributions/pdf', [ReportController::class, 'exportContributionsPdf'])->name('reports.contributions.pdf');
        Route::get('/reports/contributions/excel', [ReportController::class, 'exportContributionsExcel'])->name('reports.contributions.excel');
        // -- END OF NEW CONTRIBUTION ROUTES --

        // -- ADD THESE ROUTES FOR LOANS --
        Route::get('/reports/loans', [ReportController::class, 'loansReport'])->name('reports.loans');
        Route::get('/reports/loans/pdf', [ReportController::class, 'exportLoansPdf'])->name('reports.loans.pdf');
        Route::get('/reports/loans/excel', [ReportController::class, 'exportLoansExcel'])->name('reports.loans.excel');
        // -- END OF NEW LOAN ROUTES --
        
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