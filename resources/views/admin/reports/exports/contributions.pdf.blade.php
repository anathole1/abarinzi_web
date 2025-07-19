<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"><title>Contributions Report</title>
    <style> /* ... same CSS as members-pdf.blade.php ... */ </style>
</head>
<body>
    <div class="header"> /* ... Header content with logo, title, date ... */ </div>
    <div class="footer"> Page <span class="pagenum"></span> </div>
    <h1>Contributions Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Member</th><th>Type</th><th>Amount</th><th>Method</th><th>Status</th><th>Payment Date</th><th>Approved By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contributions as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>{{ Str::title(str_replace('_', ' ', $item->type)) }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>
                <td>{{ Str::title(str_replace('_', ' ', $item->payment_method ?? '')) }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>{{ $item->payment_date ? $item->payment_date->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $item->approver->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>