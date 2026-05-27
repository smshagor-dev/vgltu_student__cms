@extends('layouts.admin_app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <center><h2>Student List for Medical</h2></center>
    <h3><p><strong>Not Complete:</strong> <span class="text-danger">{{ $notCompleteCount }}</span> || 
    
        <strong>1st Medical Not Complete:</strong> <span class="text-danger">
            {{ \App\Models\User::whereNull('medical1')
                                ->orWhere('medical1', '')
                                ->orWhere('medical1', 'No')
                                ->count() }}
        </span> || 
        <strong>2nd Medical Not Complete:</strong> <span class="text-danger">
            {{ \App\Models\User::whereNull('medical2')
                                ->orWhere('medical2', '')
                                ->orWhere('medical2', 'No')
                                ->count() }}
        </span>
    </h3>
    
     <!-- Filter Buttons -->
    <div class="mb-3">
        <button class="btn btn-primary filter-btn" data-filter="not-complete">Not Complete</button>
        <button class="btn btn-warning filter-btn" data-filter="medical1-not-complete">1st Medical Not Complete</button>
        <button class="btn btn-success filter-btn" data-filter="medical2-not-complete">2nd Medical Not Complete</button>
    </div>
    <button class="btn btn-info mb-3" id="copyNamesBtn">Copy All Names</button>
    <!-- Search Box -->
    <input type="text" id="searchStudent" class="form-control mb-3" placeholder="Search by Name or Room Number ">

    <!-- Responsive Table -->
    <div class="table-responsive" id="printArea">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Serial</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Room Number</th>
                    <th>Mobile Number</th>
                    <th>1st Medical</th>
                    <th>2nd Medical</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="studentTable">
                @foreach ($totalStudentsListmedical as $index => $student)
                    @if($student->medical_status !== 'Complete') <!-- Only show students with 'Not Complete' status -->
                   <tr data-medical1="{{ $student->medical1 }}" data-medical2="{{ $student->medical2 }}" data-status="{{ $student->medical_status }}">
                        <td>{{ $totalStudentsListmedical->firstItem() + $index }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $student->photo) }}" 
                                 alt="Student Photo" 
                                 class="img-thumbnail clickable-photo" 
                                 width="50" height="50"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#photoModal"
                                 data-photo="{{ asset('storage/' . $student->photo) }}">
                        </td>
                        <td class="searchable name">{{ $student->full_name }}</td>
                        <td class="searchable room-number">{{ $student->room_number }}</td>
                        <td class="searchable mobile-number">
                            <a href="tel:{{ $student->mobile_number }}">{{ $student->mobile_number }}</a>
                        </td>
                        <!-- 1st Medical Checkbox -->
                        <td>
                            <input type="checkbox" class="medical-checkbox" 
                                   data-id="{{ $student->id }}" 
                                   data-field="medical1" 
                                   {{ $student->medical1 == 'Yes' ? 'checked' : '' }}>
                        </td>
                        <!-- 2nd Medical Checkbox -->
                        <td>
                            <input type="checkbox" class="medical-checkbox" 
                                   data-id="{{ $student->id }}" 
                                   data-field="medical2" 
                                   {{ $student->medical2 == 'Yes' ? 'checked' : '' }}>
                        </td>
                        <!-- Status -->
                        <td id="status-{{ $student->id }}" 
                            class="{{ $student->medical_status == 'Complete' ? 'text-success' : 'text-danger' }}">
                            {{ $student->medical_status }}
                        </td>
                        <td>
                            <button class="btn btn-secondary copy-btn" data-id="{{ $student->id }}">
                                Copy
                            </button>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $totalStudentsListmedical->links() }}
    </div>
</div>

<!-- AJAX Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.medical-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let studentId = this.dataset.id;
            let field = this.dataset.field;
            let isChecked = this.checked ? 'Yes' : 'No';

            fetch("{{ route('admin.updateMedicalStatus') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    id: studentId,
                    field: field,
                    value: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let statusCell = document.getElementById('status-' + studentId);
                    statusCell.innerText = data.new_status;
                    
                    // Change text color based on status
                    if (data.new_status === 'Complete') {
                        statusCell.classList.remove('text-danger');
                        statusCell.classList.add('text-success');

                        // Optionally, hide the row if the status is 'Complete'
                        let row = statusCell.closest('tr');
                        row.style.display = 'none';
                    } else {
                        statusCell.classList.remove('text-success');
                        statusCell.classList.add('text-danger');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});

</script>

<script>
    
document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', function() {
        let filter = this.dataset.filter;
        
        document.querySelectorAll('#studentTable tr').forEach(row => {
            let medical1 = row.getAttribute('data-medical1') || '';  // 1st medical status
            let medical2 = row.getAttribute('data-medical2') || '';  // 2nd medical status
            let medical_status = row.getAttribute('data-medical-status') || '';  // overall medical status
            let status = row.getAttribute('data-status') || '';  // general status (e.g., "Not Complete" or "Complete")

            row.style.display = 'none'; // Hide all rows initially

            // Show only if 1st medical is not complete (empty or No)
            if (filter === 'medical1-not-complete' && (medical1 === '' || medical1 === 'No')) {
                row.style.display = '';
            } 
            // Show only if 2nd medical is not complete (1st medical done, 2nd incomplete)
            else if (filter === 'medical2-not-complete' && (medical1 === 'Yes' || medical1 === '' || medical1 === 'No') && (medical2 === '' || medical2 === 'No')) {
                row.style.display = '';  
            }

            // Show only if medical status is incomplete (either medical1 or medical2 is not complete)
            else if (filter === 'medical-status-not-complete' && (medical_status !== 'Complete')) {
                row.style.display = '';  
            }
            // Show only if status is not complete
            else if (filter === 'not-complete' && status !== 'Complete') {
                row.style.display = '';
            } 
            // Show only if status is complete
            else if (filter === 'complete' && status === 'Complete') {
                row.style.display = '';
            }

            // If 'complete' filter is clicked, show only rows where both medicals are complete
            if (filter === 'complete' && medical1 === 'Yes' && medical2 === 'Yes' && medical_status === 'Complete') {
                row.style.display = '';
            }
        });
    });
});

</script>

<!-- JavaScript for Live Search and Modal -->
<script>
    document.getElementById('searchStudent').addEventListener('input', function () {
        let searchValue = this.value.toLowerCase();
        let rows = document.querySelectorAll('#studentTable tr');

        rows.forEach(row => {
            let searchableFields = row.querySelectorAll('.searchable');
            let isMatch = Array.from(searchableFields).some(field => field.textContent.toLowerCase().includes(searchValue));
            row.style.display = isMatch ? '' : 'none';
        });
    });
</script>


<!-- Bootstrap Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Student Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" alt="Student Photo" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="copyToast" class="toast align-items-center text-white bg-success border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
    <div class="d-flex">
        <div class="toast-body">
            Copied Successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>


<!-- JavaScript to Load Image in Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.clickable-photo').forEach(img => {
            img.addEventListener('click', function () {
                let photoSrc = this.getAttribute('data-photo');
                document.getElementById('modalPhoto').setAttribute('src', photoSrc);
            });
        });
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function () {
            let row = this.closest('tr');
            let name = row.querySelector('.name').innerText;
            let roomNumber = row.querySelector('.room-number').innerText;
            let mobileNumber = row.querySelector('.mobile-number a').innerText;

            let textToCopy = `Name: ${name}\nRoom Number: ${roomNumber}\nMobile: ${mobileNumber}`;

            navigator.clipboard.writeText(textToCopy).then(() => {
                showToast("Copied Successfully!");
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });

    function showToast(message) {
        let toast = document.getElementById("copyToast");
        toast.querySelector(".toast-body").innerText = message;
        toast.style.display = "block";

        setTimeout(() => {
            toast.style.display = "none";
        }, 3000); // Hide after 3 seconds
    }
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    let activeFilter = "all"; // Store the active filter
    
    // Handle filter buttons
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function () {
            activeFilter = this.dataset.filter; // Update active filter
            applyFilter(activeFilter);
        });
    });

    // Handle Copy Button Click
    document.getElementById('copyNamesBtn').addEventListener('click', function () {
        let visibleRows = document.querySelectorAll('#studentTable tr:not([style*="display: none"]) .name');
        let names = Array.from(visibleRows)
                         .map(nameCell => nameCell.innerText.trim())
                         .join('\n');

        if (names) {
            navigator.clipboard.writeText(names).then(() => {
                showToast("Copied Successfully!");
            }).catch(err => {
                console.error('Failed to copy names: ', err);
            });
        } else {
            showToast("No names available to copy!");
        }
    });

    // Show Toast Message
    function showToast(message) {
        let toast = document.getElementById("copyToast");
        toast.querySelector(".toast-body").innerText = message;
        toast.style.display = "block";

        setTimeout(() => {
            toast.style.display = "none";
        }, 3000);
    }

    // Apply filter function (already implemented in your filter logic)
    function applyFilter(filter) {
        document.querySelectorAll('#studentTable tr').forEach(row => {
            let medical1 = row.getAttribute('data-medical1') || '';  
            let medical2 = row.getAttribute('data-medical2') || '';  
            let status = row.getAttribute('data-status') || '';  

            row.style.display = 'none'; 

            if (filter === 'medical1-not-complete' && (medical1 === '' || medical1 === 'No')) {
                row.style.display = '';
            } else if (filter === 'medical2-not-complete' && (medical1 === 'Yes' || medical1 === '' || medical1 === 'No') && (medical2 === '' || medical2 === 'No')) {
                row.style.display = '';  
            } else if (filter === 'not-complete' && status !== 'Complete') {
                row.style.display = '';
            }
        });
    }
});
</script>





@endsection
