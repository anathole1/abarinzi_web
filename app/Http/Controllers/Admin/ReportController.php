<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\Contribution;
use App\Models\Loan;
use Illuminate\Http\Request;
// For PDF/Excel later:
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport; // You would create this export class

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
    public function contributionsReport(Request $request)
    {
        // TODO: Add filtering logic similar to membersReport
        $query = Contribution::with(['user', 'approver'])->orderBy('payment_date', 'desc');
        $contributions = $query->get(); // Get all for now
        return view('admin.reports.contributions', compact('contributions'));
    }

    public function loansReport(Request $request)
    {
        // TODO: Add filtering logic
        $query = Loan::with(['user', 'approver'])->orderBy('application_date', 'desc');
        $loans = $query->get(); // Get all for now
        return view('admin.reports.loans', compact('loans'));
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