@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <center><h2>Medical Status</h2></center>
    
    <h3><p><strong>Status:</strong> 
        <span id="status-{{ auth()->user()->id }}" 
              class="{{ auth()->user()->medical_status == 'Complete' ? 'text-success' : 'text-danger' }}">
              {{ auth()->user()->medical_status }}
        </span>
    </p></h3>

    <!-- Medical Form for User -->
    <div class="card p-3">
        <h4>Medical Checkup Submission</h4>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>1st Medical</th>
                    <th>2nd Medical</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <!-- 1st Medical Checkbox -->
                    <td>
                        <input type="checkbox" id="medical1" class="medical-checkbox" 
                               data-id="{{ auth()->user()->id }}" 
                               data-field="medical1" 
                               {{ auth()->user()->medical1 == 'Yes' ? 'checked' : '' }}>
                    </td>
                    <!-- 2nd Medical Checkbox -->
                    <td>
                        <input type="checkbox" id="medical2" class="medical-checkbox" 
                               data-id="{{ auth()->user()->id }}" 
                               data-field="medical2" 
                               {{ auth()->user()->medical2 == 'Yes' ? 'checked' : '' }}>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- AJAX Script for Updating Medical Status -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Fetch the current medical status on page load
    fetch("{{ route('user.getMedicalStatus') }}")
        .then(response => response.json())
        .then(data => {
            if (data.medical_status) {
                let statusCell = document.getElementById('status-' + {{ auth()->user()->id }});
                statusCell.innerText = data.medical_status;

                // Set status color
                if (data.medical_status === 'Complete') {
                    statusCell.classList.remove('text-danger');
                    statusCell.classList.add('text-success');
                } else {
                    statusCell.classList.remove('text-success');
                    statusCell.classList.add('text-danger');
                }

                // Set checkbox values based on current status
                document.getElementById('medical1').checked = (data.medical1 === 'Yes');
                document.getElementById('medical2').checked = (data.medical2 === 'Yes');
            }
        })
        .catch(error => console.error('Error fetching status:', error));

    // Update medical status on checkbox change
    document.querySelectorAll('.medical-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let studentId = this.dataset.id;
            let field = this.dataset.field;
            let isChecked = this.checked ? 'Yes' : 'No';

            fetch("{{ route('user.updateMedicalStatus') }}", {
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

@endsection
