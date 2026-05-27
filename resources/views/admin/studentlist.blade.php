@extends('layouts.admin_app')

@section('content')
<div class="admin-page">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <section class="admin-hero-card">
        <h2>Student List</h2>
        <p>Operational student table with live page search, print support, and quick actions for profile review and access recovery.</p>
    </section>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar__title">
                <h3>Student Records</h3>
                <p>Search the current page and print the table when needed.</p>
            </div>
            <div class="admin-actions-inline">
                <button onclick="printSection()" class="btn btn-primary">Print List</button>
                <span class="admin-chip">
                    <i class="fas fa-users"></i>
                    <span>{{ method_exists($totalStudentsList, 'total') ? $totalStudentsList->total() : $totalStudentsList->count() }} Students</span>
                </span>
            </div>
        </div>

        <div class="mb-4">
            <input type="text" id="searchStudent" class="form-control" placeholder="Search anything on this page...">
        </div>

        <div class="admin-data-card" id="printArea">
            <div class="admin-table-wrap">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
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
                            <th class="no-print">Medical</th>
                            <th class="no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody id="studentTable">
                        @foreach ($totalStudentsList as $index => $student)
                            <tr>
                                <td>{{ $totalStudentsList->firstItem() + $index }}</td>
                                <td class="searchable room_number">{{ $student->room_number }}</td>
                                <td class="searchable name">{{ $student->full_name }}</td>
                                <td class="searchable">
                                    <a href="tel:{{ $student->mobile_number }}">{{ $student->mobile_number }}</a>
                                </td>
                                <td class="searchable country">{{ $student->country }}</td>
                                <td class="searchable religion">{{ $student->religion }}</td>
                                <td class="searchable course_type">{{ $student->course_type }}</td>
                                <td class="searchable department">{{ $student->department }}</td>
                                <td class="searchable course_language">{{ $student->course_language ?: 'Russian' }}</td>
                                <td class="no-print" style="color: {{ $student->medical_status == 'Complete' ? 'green' : ($student->medical_status == 'Not Complete' ? 'red' : 'black') }}">
                                    {{ $student->medical_status }}
                                </td>
                                <td class="no-print">
                                    <div class="admin-actions-inline">
                                        <a href="{{ route('admin.users.view', ['id' => $student->id]) }}" class="btn btn-sm btn-info">View</a>
                                        <form action="{{ route('admin.users.delete', ['id' => $student->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                        </form>
                                        <form action="{{ route('admin.forgetPassword', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reset this user\'s password?');" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">Reset Password</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="admin-pagination">
        {{ $totalStudentsList->links() }}
    </div>
</div>

<script>
document.getElementById('searchStudent').addEventListener('input', function () {
    let searchValue = this.value.toLowerCase().trim();
    let searchTerms = searchValue.split(',').map(term => term.trim()).filter(term => term.length > 0);
    let rows = document.querySelectorAll('#studentTable tr');

    rows.forEach(row => {
        let name = row.querySelector('.name')?.textContent.toLowerCase() || '';
        let country = row.querySelector('.country')?.textContent.toLowerCase() || '';
        let religion = row.querySelector('.religion')?.textContent.toLowerCase() || '';
        let roomNumber = row.querySelector('.room_number')?.textContent.toLowerCase() || '';
        let mobileNumber = row.querySelector('.mobile_number')?.textContent.toLowerCase() || '';
        let courseType = row.querySelector('.course_type')?.textContent.toLowerCase() || '';
        let department = row.querySelector('.department')?.textContent.toLowerCase() || '';
        let courseLanguage = row.querySelector('.course_language')?.textContent.toLowerCase() || '';

        let isMatch = searchTerms.every(term =>
            name.includes(term) ||
            country.includes(term) ||
            religion.includes(term) ||
            roomNumber.includes(term) ||
            mobileNumber.includes(term) ||
            courseType.includes(term) ||
            department.includes(term) ||
            courseLanguage.includes(term)
        );

        row.style.display = isMatch ? '' : 'none';
    });
});

function printSection() {
    let printContents = document.getElementById('printArea').innerHTML;
    let printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write('<html><head><title>Print Student List</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .no-print { display: none !important; }
        @media print { button, input, .no-print { display: none !important; } }
    `);
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h2 style="text-align: center;">Student List</h2>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection
