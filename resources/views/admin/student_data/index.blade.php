@extends('layouts.admin_app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Student Data List</h2>
        <a href="{{ route('admin.studentsdata.index') }}" class="btn btn-primary">Refresh</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- In-Page Search Bar -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by Room Number, Name, or Date">
    </div>

    <!-- Wrap the table in a responsive container -->
    <div class="table-responsive">
        <table class="table table-bordered" id="studentTable">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Room Number</th>
                    <th>Full Name</th>
                    <th>Passport Number</th>
                    <th>Visa Start Date</th>
                    <th>Visa Expiry Date</th>
                    <th>Passport Photo</th>
                    <th>Visa Photo</th>
                    <th>Green Card Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr>
                    <td>{{ $students->firstItem() + $loop->index }}</td>
                    <td>{{ $student->user->room_number ?? 'N/A' }}</td>
                    <td>{{ $student->user->full_name ?? 'N/A' }}</td>
                    <td>{{ $student->passport_number }}</td>
                    <td>{{ $student->visa_start_date }}</td>
                    <td>{{ $student->visa_expiry_date }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $student->passport_photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $student->passport_photo) }}" width="80" height="50">
                        </a>
                    </td>
                    <td>
                        <a href="{{ asset('storage/' . $student->visa_photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $student->visa_photo) }}" width="80" height="50">
                        </a>
                    </td>
                    <td>
                        <a href="{{ asset('storage/' . $student->green_card_photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $student->green_card_photo) }}" width="80" height="50">
                        </a>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- View Button -->
                            <a href="{{ route('admin.users.view', $student->user->id ?? $student->id) }}" class="btn btn-info btn-sm mx-1">View</a>
                    
                            <!-- Edit Button -->
                            <a href="{{ route('admin.studentsdata.edit', $student->id) }}" class="btn btn-warning btn-sm mx-1">Edit</a>
                    
                            <!-- Delete Form -->
                            <form action="{{ route('admin.studentsdata.delete', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mx-1">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $students->links() }}
    </div>
</div>
<script>
@media (max-width: 767.98px) {
    #studentTable th,
    #studentTable td {
        font-size: 12px; /* Smaller font size for mobile */
    }

    #studentTable img {
        width: 60px; /* Smaller images for mobile */
        height: 40px;
    }

    #studentTable .btn {
        padding: 0.25rem 0.5rem; /* Smaller buttons for mobile */
        font-size: 12px;
    }
}
</script>
@endsection

@section('scripts')
<script>
    // In-page search filter
    document.getElementById("searchInput").addEventListener("keyup", function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#studentTable tbody tr");

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
</script>
@endsection
