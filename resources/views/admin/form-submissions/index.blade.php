@extends('layouts.admin_app')

@section('content')

<div style="max-width: 100%; padding: 20px; font-family: Arial, sans-serif;">
    <h2 style="margin-bottom: 20px; font-size: 1.8rem; text-align: center; color: #333;">Custom Fields</h2>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; margin: 0 auto; font-size: 14px; color: #555;">
            <thead style="background-color: #007bff; color: #fff; text-align: left;">
                <tr>
                    <th style="padding: 10px; border: 1px solid #ddd;">SL</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Field Label</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Field Type</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Options</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">User Count</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customFieldData as $index => $field)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">{{ $customFieldData->firstItem() + $index }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $field->field_label }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">{{ ucfirst($field->field_type) }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">
                            <!-- Nested table for options and counts -->
                            <table style="width: 100%; border-collapse: collapse;">
                                @foreach ($field->options as $key => $option)
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd; width: 50%;">
                                            <strong>{{ $key + 1 }}.</strong> {{ $option }}
                                        </td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                                            <span style="color: #007bff;">({{ $field->option_counts[$option] ?? 0 }} submissions)</span>
                                        </td>
                                        <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                                            <!-- View button with modal trigger -->
                                            <button 
                                                style="padding: 8px 15px; background-color: #28a745; color: #fff; border: none; border-radius: 5px; font-size: 14px; cursor: pointer;"
                                                data-toggle="modal" 
                                                data-target="#viewOptionModal-{{ $field->field_id }}-{{ $key }}"
                                            >
                                                View
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="viewOptionModal-{{ $field->field_id }}-{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="viewOptionModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="viewOptionModalLabel">Option Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h6>Option: {{ $option }}</h6>
                                                            <!-- Table inside the modal with responsive scrolling -->
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Room</th>
                                                                            <th>Problem</th>
                                                                            <th>Description</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($userFieldData as $userField)
                                                                            @php
                                                                                // Treat the value as plain text and check if the option exists in the value field
                                                                                $values = explode(',', $userField->value); // If options are stored as comma-separated values
                                                                                $descriptions = $userField->description; // The description field is also stored in user_field_data
                                                                            @endphp
                                                                            
                                                                            <!-- Check if the selected option exists in the value array -->
                                                                            @if (in_array($option, $values))
                                                                                <tr>
                                                                                    <td>{{ $userField->full_name }}</td>
                                                                                    <td>{{ $userField->room_number }}</td>
                                                                                    <td>
                                                                                        <!-- Show the value directly from the user_field_data -->
                                                                                        {{ $userField->value }} <!-- Display the value directly -->
                                                                                    </td>
                                                                                    <td>
                                                                                        <!-- Show the description directly from the user_field_data -->
                                                                                        {{ $userField->description }} <!-- Display the description directly -->
                                                                                    </td>
                                                                                    <td>{{ $userField->status }}</td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
        
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                            Total Submit {{ $field->submission_count }} user
                        </td>

                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                            <!-- Update the View button -->
                            <a href="{{ route('admin.form-submissions.view-users', $field->field_id) }}"  
                                style="padding: 8px 15px; background-color: #28a745; color: #fff; border: none; border-radius: 5px; font-size: 14px; cursor: pointer; text-decoration: none; font-weight: bold; display: inline-block;">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $customFieldData->links() }}
</div>



<style>
/* Ensures the modal doesn't grow too large on mobile devices */
@media (max-width: 576px) {
    .modal-dialog {
        max-width: 100%; /* Ensures it takes up full screen on small devices */
    }

    .modal-body {
        padding: 1rem; /* Reduce padding for smaller screens */
    }

    .table-responsive {
        max-height: 300px; /* Optional: sets a max height for the table */
        overflow-y: auto; /* Allows scrolling for larger tables */
    }
}
</style>

<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (and jQuery and Popper.js for Bootstrap's JavaScript functionality) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
