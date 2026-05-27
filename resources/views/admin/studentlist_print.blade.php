<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Student List Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        h2, p { margin: 0 0 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #111827; padding: 8px; text-align: left; font-size: 12px; }
        th { background: #e5e7eb; }
        .muted { color: #4b5563; }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <h2>Full Student List</h2>
    <p class="muted">
        Total: {{ $students->count() }} students
        @if ($search)
            | Search: "{{ $search }}"
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>Serial</th>
                <th>Room</th>
                <th>Name</th>
                <th>Number</th>
                <th>Country</th>
                <th>Religion</th>
                <th>Course</th>
                <th>Department</th>
                <th>Language</th>
                <th>Medical</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->room_number }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->mobile_number }}</td>
                    <td>{{ $student->country }}</td>
                    <td>{{ $student->religion }}</td>
                    <td>{{ $student->course_type }}</td>
                    <td>{{ $student->department }}</td>
                    <td>{{ $student->course_language ?: 'Russian' }}</td>
                    <td>{{ $student->medical_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
