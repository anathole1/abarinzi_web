<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Loans Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h1 { text-align: center; margin-bottom: 20px; font-size: 16px; }
        .header, .footer { width: 100%; position: fixed; font-size: 8px; }
        .header { top: -50px; left: 0px; right: 0px; height: 40px; }
        .footer { bottom: -30px; left: 0px; right: 0px; height: 25px; text-align: center; }
        .pagenum:before { content: counter(page); }
        .logo { max-height: 35px; float: left; margin-right: 10px; }
        .header-info { text-align: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="header">
        <div class="clearfix">
            <img src="{{ public_path('images/your-logo.png') }}" alt="Logo" class="logo">
            <div class="header-info">
                {{ $appName ?? config('app.name') }}<br>
                Loans Report<br>
                Generated: {{ $reportDate ?? now()->format('F j, Y, g:i a') }}
            </div>
        </div>
    </div>
    <div class="footer">Page <span class="pagenum"></span></div>

    <h1>Loans Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Member</th>
                <th>Amount Appr.</th>
                <th>Term</th>
                <th>Rate (Mo)</th>
                <th>Total Repay</th>
                <th>Outstanding</th>
                <th>Status</th>
                <th>App. Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>#{{ $loan->id }}</td>
                <td>{{ $loan->user->name ?? 'N/A' }}</td>
                <td>{{ number_format($loan->amount_approved ?? $loan->amount_requested, 2) }}</td>
                <td>{{ $loan->term_months }} mo.</td>
                <td>{{ $loan->interest_rate }}%</td>
                <td>{{ $loan->display_total_repayment ? number_format($loan->display_total_repayment, 2) : 'N/A' }}</td>
                <td>{{ $loan->outstanding_balance !== null ? number_format($loan->outstanding_balance, 2) : 'N/A' }}</td>
                <td>{{ ucfirst($loan->status) }}</td>
                <td>{{ $loan->application_date->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>