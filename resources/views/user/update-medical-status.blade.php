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

    <div class="card p-3 mb-3">
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
                    <td>
                        <input type="checkbox" id="medical1" class="medical-checkbox" 
                               data-id="{{ auth()->user()->id }}" 
                               data-field="medical1" 
                               {{ auth()->user()->medical1 == 'Yes' ? 'checked' : '' }}>
                        <span id="status-medical1" class="{{ auth()->user()->medical1 == 'Yes' ? 'text-success' : 'text-danger' }}">
                            {{ auth()->user()->medical1 == 'Yes' ? 'Complete' : 'Incomplete' }}
                        </span>
                    </td>
                    <td>
                        <input type="checkbox" id="medical2" class="medical-checkbox" 
                               data-id="{{ auth()->user()->id }}" 
                               data-field="medical2" 
                               {{ auth()->user()->medical2 == 'Yes' ? 'checked' : '' }}>
                        <span id="status-medical2" class="{{ auth()->user()->medical2 == 'Yes' ? 'text-success' : 'text-danger' }}">
                            {{ auth()->user()->medical2 == 'Yes' ? 'Complete' : 'Incomplete' }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch("{{ route('user.getMedicalStatus') }}")
        .then(response => response.json())
        .then(data => {
            if (data.medical_status) {
                let statusCell = document.getElementById('status-' + {{ auth()->user()->id }});
                statusCell.innerText = data.medical_status;

                if (data.medical_status === 'Complete') {
                    statusCell.classList.remove('text-danger');
                    statusCell.classList.add('text-success');
                } else {
                    statusCell.classList.remove('text-success');
                    statusCell.classList.add('text-danger');
                }

                document.getElementById('medical1').checked = (data.medical1 === 'Yes');
                document.getElementById('medical2').checked = (data.medical2 === 'Yes');
                document.getElementById('status-medical1').innerText = data.medical1 === 'Yes' ? 'Complete' : 'Incomplete';
                document.getElementById('status-medical1').classList.toggle('text-success', data.medical1 === 'Yes');
                document.getElementById('status-medical1').classList.toggle('text-danger', data.medical1 !== 'Yes');
                document.getElementById('status-medical2').innerText = data.medical2 === 'Yes' ? 'Complete' : 'Incomplete';
                document.getElementById('status-medical2').classList.toggle('text-success', data.medical2 === 'Yes');
                document.getElementById('status-medical2').classList.toggle('text-danger', data.medical2 !== 'Yes');
            }
        })
        .catch(error => console.error('Error fetching status:', error));

    document.querySelectorAll('.medical-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            let studentId = this.dataset.id;
            let field = this.dataset.field;
            let isChecked = this.checked ? 'Yes' : 'No';

            // Update the status immediately based on the checkbox
            let statusLabel = document.getElementById('status-' + studentId);
            let statusText = this.checked ? 'Complete' : 'Incomplete';
            let statusClass = this.checked ? 'text-success' : 'text-danger';
            document.getElementById('status-' + field).innerText = statusText;
            document.getElementById('status-' + field).className = statusClass;

            // Send the updated medical status to the backend
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
                    // Optionally, you can update the status text from the server response here.
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>

@endsection
