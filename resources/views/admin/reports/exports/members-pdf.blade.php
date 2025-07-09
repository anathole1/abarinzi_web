<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Members Report</title>
    <style>
        @page {
            margin: 70px 25px 50px 25px; /* top, right, bottom, left margins */
        }
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Good for UTF-8 characters */
            font-size: 9px; /* Adjust base font size for landscape */
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ccc; /* Lighter border */
            padding: 5px;
            text-align: left;
            word-wrap: break-word; /* Help prevent overflow on long content */
        }
        th {
            background-color: #e9ecef; /* Lighter header */
            font-weight: bold;
        }
        .header, .footer {
            width: 100%;
            position: fixed; /* Fixed positioning for header/footer */
            font-size: 8px;
        }
        .header {
            top: -50px; /* Adjust based on your top margin */
            left: 0px;
            right: 0px;
            height: 40px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        .footer {
            bottom: -30px; /* Adjust based on your bottom margin */
            left: 0px;
            right: 0px;
            height: 25px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
        .pagenum:before {
            content: counter(page); /* Page numbering */
        }
        .logo {
            max-height: 35px; /* Adjust as needed */
            float: left; /* Simple float for logo placement */
            margin-right: 10px;
        }
        .report-title-header {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header-info {
            text-align: right;
            font-size: 8px;
        }
        /* Clearfix for floated elements if needed */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="clearfix">
            {{-- Make sure logo path is correct from public directory --}}
            <img src="{{ public_path('images/your-logo.png') }}" alt="Logo" class="logo">
            <div class="header-info">
                {{ $appName ?? config('app.name') }}<br>
                Members Report<br>
                Generated: {{ $reportDate ?? now()->format('F j, Y, g:i a') }}
            </div>
        </div>
    </div>

    <div class="footer">
        Page <span class="pagenum"></span>
    </div>

    {{-- The main content starts after the header's height --}}
    <h1 class="report-title-header" style="margin-top: 0;">Members List</h1> {{-- Removed margin-bottom from h1, adjust main title styling --}}

    @if($members->isEmpty())
        <p>No members found matching criteria.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Acc #</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Profile Email</th>
                    <th>User Email</th>
                    <th>Phone</th>
                    <th>Nat. ID</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Joined Assoc.</th>
                    <th>Left EFOTEC</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $profile)
                <tr>
                    <td>{{ $profile->accountNo }}</td>
                    <td>{{ $profile->first_name }}</td>
                    <td>{{ $profile->last_name }}</td>
                    <td>{{ $profile->email }}</td>
                    <td>{{ $profile->user->email }}</td>
                    <td>{{ $profile->phone_number }}</td>
                    <td>{{ $profile->national_id }}</td>
                    <td>{{ $profile->memberCategory->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($profile->status) }}</td>
                    <td>{{ $profile->dateJoined ? $profile->dateJoined->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $profile->year_left_efotec ?: 'N/A' }}</td>
                    <td>{{ $profile->current_location ?: 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>