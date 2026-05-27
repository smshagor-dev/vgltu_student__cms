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
                <p>Search directly from the database and print the full filtered list when needed.</p>
            </div>
            <div class="admin-actions-inline">
                <button onclick="printSection()" class="btn btn-primary">Print List</button>
                <span class="admin-chip">
                    <i class="fas fa-users"></i>
                    <span id="studentCount">{{ method_exists($totalStudentsList, 'total') ? $totalStudentsList->total() : $totalStudentsList->count() }} Students</span>
                </span>
            </div>
        </div>

        <div class="mb-4">
            <input type="text" id="searchStudent" class="form-control" value="{{ $search ?? '' }}" placeholder="Search from database by name, room, number, country, department...">
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
                        @include('admin.partials.studentlist_rows')
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="admin-pagination" id="studentPagination">
        {{ $totalStudentsList->links() }}
    </div>
</div>

<script>
const searchInput = document.getElementById('searchStudent');
const tableBody = document.getElementById('studentTable');
const paginationWrap = document.getElementById('studentPagination');
const studentCount = document.getElementById('studentCount');
let studentSearchTimeout = null;

function loadStudents(page = 1) {
    const search = searchInput.value.trim();
    const url = new URL('{{ route('admin.studentlist') }}', window.location.origin);
    url.searchParams.set('page', page);

    if (search) {
        url.searchParams.set('search', search);
    }

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = data.rows;
            paginationWrap.innerHTML = data.pagination;
            studentCount.textContent = `${data.count} Students`;
            const browserUrl = new URL('{{ route('admin.studentlist') }}', window.location.origin);

            if (search) {
                browserUrl.searchParams.set('search', search);
            }

            if (page > 1) {
                browserUrl.searchParams.set('page', page);
            }

            window.history.replaceState({}, '', browserUrl);
        })
        .catch(() => {
            tableBody.innerHTML = '<tr><td colspan="11" class="text-center text-danger">Unable to load students right now.</td></tr>';
        });
}

searchInput.addEventListener('input', function () {
    clearTimeout(studentSearchTimeout);
    studentSearchTimeout = setTimeout(() => loadStudents(1), 350);
});

paginationWrap.addEventListener('click', function (event) {
    const link = event.target.closest('a');

    if (!link) {
        return;
    }

    event.preventDefault();
    const url = new URL(link.href);
    const page = url.searchParams.get('page') || 1;
    loadStudents(page);
});

function printSection() {
    const url = new URL('{{ route('admin.studentlist.print') }}', window.location.origin);
    const search = searchInput.value.trim();

    if (search) {
        url.searchParams.set('search', search);
    }

    window.open(url.toString(), '_blank');
}
</script>
@endsection
