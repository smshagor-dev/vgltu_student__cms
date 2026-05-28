<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students By Floor Report</title>
    <style>
        body {
            font-family: sans-serif;
            color: #1f2937;
            font-size: 11px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .meta {
            margin-bottom: 18px;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: middle;
            text-align: left;
        }

        th {
            background: #f3f4f6;
            font-weight: 700;
        }

    </style>
</head>
<body>
    <h1>Students By Floor Report</h1>
    <div class="meta">
        Religion: {{ $selectedReligion ?: 'All Religions' }} |
        Country: {{ $selectedCountry ?: 'All Countries' }} |
        Total Students: {{ count($reportEntries) }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Room Number</th>
                <th>Floor</th>
                <th>Block</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportEntries as $entry)
                <tr>
                    <td>{{ $entry['name'] }}</td>
                    <td>{{ $entry['room_number'] }}</td>
                    <td>{{ $entry['floor'] }}</td>
                    <td>{{ $entry['block'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
