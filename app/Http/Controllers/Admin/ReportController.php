<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\Contribution;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Models\User;
// For PDF/Excel later:
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport; // You would create this export class
use App\Exports\ContributionsExport; // Import
use App\Exports\LoansExport;         // Import

class ReportController extends Controller
{
    public function __construct()
    {
        // Ensure only admins can access reports
        // $this->middleware('permission:generate reports'); // Or use role middleware on routes
    }
    private function getFilteredMembers(Request $request)
    {
        $query = MemberProfile::with(['user', 'memberCategory'])->orderBy('last_name')->orderBy('first_name');
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('category_filter')) {
            $query->where('member_category_id', $request->category_filter);
        }
        // Add search logic if you have it on the HTML report page
        return $query->get();
    }
    public function membersReport(Request $request)
    {
        $members = $this->getFilteredMembers($request);
        // For HTML display, you might want to paginate if the list is very long
        // $membersPaginated = $query->paginate(50)->appends($request->query());
        // return view('admin.reports.members', compact('membersPaginated'));

        return view('admin.reports.members', compact('members'));
    }

    public function exportMembersPdf(Request $request)
    {
        $members = $this->getFilteredMembers($request);
        $reportDate = now()->format('F j, Y, g:i a');
        $appName = config('app.name');

        $pdf = Pdf::loadView('admin.reports.exports.members-pdf', compact('members', 'reportDate', 'appName'))
                    ->setPaper('a4', 'landscape'); // Set paper size and orientation

        return $pdf->download('members-report-' . now()->format('YmdHis') . '.pdf');
    
    }

    public function exportMembersExcel(Request $request)
    {
        $members = $this->getFilteredMembers($request); // Get filtered data
        return Excel::download(new MembersExport($members), 'members-report-' . now()->format('YmdHis') . '.xlsx');
    }


    // Basic stubs for other reports
    // public function contributionsReport(Request $request)
    // {
    //     // TODO: Add filtering logic similar to membersReport
    //     $query = Contribution::with(['user', 'approver'])->orderBy('payment_date', 'desc');
    //     $contributions = $query->get(); // Get all for now
    //     return view('admin.reports.contributions', compact('contributions'));
    // }

    // public function loansReport(Request $request)
    // {
    //     // TODO: Add filtering logic
    //     $query = Loan::with(['user', 'approver'])->orderBy('application_date', 'desc');
    //     $loans = $query->get(); // Get all for now
    //     return view('admin.reports.loans', compact('loans'));
    // }
     private function getFilteredContributions(Request $request) {
        $query = Contribution::with(['user', 'approver'])->orderBy('payment_date', 'desc');
        if ($request->filled('user_filter')) { $query->where('user_id', $request->user_filter); }
        if ($request->filled('type_filter')) { $query->where('type', $request->type_filter); }
        if ($request->filled('status_filter')) { $query->where('status', $request->status_filter); }
        if ($request->filled('date_from')) { $query->whereDate('payment_date', '>=', $request->date_from); }
        if ($request->filled('date_to')) { $query->whereDate('payment_date', '<=', $request->date_to); }
        return $query->get();
    }

    public function contributionsReport(Request $request) {
        $contributions = $this->getFilteredContributions($request);
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'member'))->orderBy('name')->get();
        return view('admin.reports.contributions', compact('contributions', 'users'));
    }

    public function exportContributionsPdf(Request $request) {
        $contributions = $this->getFilteredContributions($request);
        $pdf = Pdf::loadView('admin.reports.exports.contributions-pdf', compact('contributions'));
        return $pdf->setPaper('a4', 'landscape')->download('contributions-report-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportContributionsExcel(Request $request) {
        $contributions = $this->getFilteredContributions($request);
        return Excel::download(new ContributionsExport($contributions), 'contributions-report-' . now()->format('YmdHis') . '.xlsx');
    }

    // --- LOANS ---
    private function getFilteredLoans(Request $request) {
        $query = Loan::with(['user', 'approver'])->orderBy('application_date', 'desc');
        if ($request->filled('user_filter')) { $query->where('user_id', $request->user_filter); }
        if ($request->filled('status_filter')) { $query->where('status', $request->status_filter); }
        // Add date filters for application_date
        return $query->get();
    }

    public function loansReport(Request $request) {
        $loans = $this->getFilteredLoans($request);
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'member'))->orderBy('name')->get();
        return view('admin.reports.loans', compact('loans', 'users'));
    }

    public function exportLoansPdf(Request $request) {
        $loans = $this->getFilteredLoans($request);
        $pdf = Pdf::loadView('admin.reports.exports.loans-pdf', compact('loans'));
        return $pdf->setPaper('a4', 'landscape')->download('loans-report-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportLoansExcel(Request $request) {
        $loans = $this->getFilteredLoans($request);
        return Excel::download(new LoansExport($loans), 'loans-report-' . now()->format('YmdHis') . '.xlsx');
    }

    // public function membersReport(Request $request)
    // {
    //     // Add filtering capabilities similar to MemberProfile index
    //     $query = MemberProfile::with(['user', 'memberCategory'])->orderBy('created_at', 'desc');

    //     if ($request->filled('status_filter')) {
    //         $query->where('status', $request->status_filter);
    //     }
    //     if ($request->filled('category_filter')) {
    //         $query->where('member_category_id', $request->category_filter);
    //     }
    //     // Add search for name, email, accountNo

    //     $members = $query->get(); // Get all for report, or paginate if displaying HTML first

    //     // For now, just display an HTML table. Later, you can add export options.
    //     return view('admin.reports.members', compact('members'));
    // }

    // public function contributionsReport(Request $request)
    // {
    //     $query = Contribution::with(['user', 'approver'])->orderBy('payment_date', 'desc');
    //     // Add filters for user, status, date range, type
    //     $contributions = $query->get();
    //     return view('admin.reports.contributions', compact('contributions'));
    // }

    // public function loansReport(Request $request)
    // {
    //     $query = Loan::with(['user', 'approver'])->orderBy('application_date', 'desc');
    //     // Add filters for user, status, date range
    //     $loans = $query->get();
    //     return view('admin.reports.loans', compact('loans'));
    // }

    // Example for PDF export (using barryvdh/laravel-dompdf)
    // public function exportMembersPdf()
    // {
    //     $members = MemberProfile::with(['user', 'memberCategory'])->get();
    //     $pdf = Pdf::loadView('admin.reports.exports.members-pdf', compact('members'));
    //     return $pdf->download('members-report.pdf');
    // }
}