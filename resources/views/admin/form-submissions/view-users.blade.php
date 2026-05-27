@extends('layouts.admin_app')

@section('content')

<!-- Back Button -->
<button onclick="goBack()" style="margin-bottom: 15px; padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
    ← Go Back
</button>
<script>
    function goBack() {
        window.history.back();
    }
</script>

<!-- Your HTML Content Here -->
<div style="max-width: 100%; padding: 20px; font-family: Arial, sans-serif;">
    @foreach ($users as $user)
        @foreach ($user->fieldData->groupBy('field_id') as $fieldId => $groupedFields)
    <h2 style="margin-bottom: 20px; font-size: 1.8rem; text-align: center; color: #333;">Users Who Submitted Data in {{ $groupedFields->first()->customField->field_label }}</h2>
    @endforeach
                @endforeach
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; margin: 0 auto; font-size: 14px; color: #555;">
            <thead style="background-color: #007bff; color: #fff; text-align: left;">
                <tr>
                    <th style="padding: 10px; border: 1px solid #ddd;">User Name</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Room Number</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Field Label</th>
                    <!--<th style="padding: 10px; border: 1px solid #ddd;">Field Type</th>-->
                    <th style="padding: 10px; border: 1px solid #ddd;">Submitted Data</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Description</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    @foreach ($user->fieldData->groupBy('field_id') as $fieldId => $groupedFields)
                        <tr id="row-{{ $groupedFields->first()->id }}" style="border-bottom: 1px solid #ddd;">
                            @if ($loop->first)
                                <td style="padding: 10px; border: 1px solid #ddd;" rowspan="{{ $user->fieldData->count() }}">
                                    <center>{{ $user->full_name }}</center>
                                </td>
                                <td style="padding: 10px; border: 1px solid #ddd;" rowspan="{{ $user->fieldData->count() }}">
                                    <center>{{ $user->room_number }}</center>
                                </td>
                            @endif

                            <!-- Field Label (Grouped Together) -->
                            <td style="padding: 10px; border: 1px solid #ddd;" rowspan="{{ $groupedFields->count() }}">
                                {{ $groupedFields->first()->customField->field_label }}
                            </td>

                            <!-- Field Type (Grouped Together) -->
                            <!--<td style="padding: 10px; border: 1px solid #ddd;" rowspan="{{ $groupedFields->count() }}">-->
                            <!--    {{ ucfirst($groupedFields->first()->customField->field_type) }}-->
                            <!--</td>-->

                            @foreach ($groupedFields as $index => $fieldData)
                                @if ($index > 0)
                                    <tr style="border-bottom: 1px solid #ddd;">
                                @endif

                                <!-- Submitted Data -->
                                <td style="padding: 10px; border: 1px solid #ddd;">
                                    @if ($fieldData->customField->field_type == 'multiple_choice')
                                        {{ implode(', ', explode(',', $fieldData->value)) }}
                                    @else
                                        {{ $fieldData->value }}
                                    @endif
                                </td>

                                <!-- Description -->
                                <td style="padding: 10px; border: 1px solid #ddd;">
                                    {{ $fieldData->description ?? 'No Description Available' }}
                                </td>

                                <!-- For the Status Change (AJAX-enabled) -->
                                <td style="padding: 10px; border: 1px solid #ddd;">
                                    <button id="status-btn-{{ $fieldData->id }}" class="btn btn-{{ $fieldData->status === 'solved' ? 'success' : 'warning' }}" 
                                            data-userid="{{ $user->id }}" data-valueid="{{ $fieldData->id }}" 
                                            data-status="{{ $fieldData->status }}">
                                        {{ ucfirst($fieldData->status) }}
                                    </button>
                                </td>

                                <!-- For Deletion (AJAX-enabled) -->
                                <td style="padding: 10px; border: 1px solid #ddd;">
                                    <button class="btn btn-danger delete-btn" data-userid="{{ $user->id }}" 
                                            data-valueid="{{ $fieldData->id }}">Delete</button>
                                </td>

                                @if ($index > 0)
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $users->links() }}
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Change Status via AJAX
        $('.btn').on('click', function(e) {
            e.preventDefault();
            var userId = $(this).data('userid');
            var valueId = $(this).data('valueid');
            var status = $(this).data('status');

            $.ajax({
                url: '{{ route("admin.user-from-submission-data.update-value-status", ["userId" => ":userId", "valueId" => ":valueId"]) }}'.replace(':userId', userId).replace(':valueId', valueId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status === 'solved' ? 'pending' : 'solved',
                },
                success: function(response) {
                    if (response.success) {
                        // Update the status text and button color
                        var newStatus = status === 'solved' ? 'pending' : 'solved';
                        var button = $('#status-btn-' + valueId);
                        button.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                        button.removeClass('btn-' + (status === 'solved' ? 'success' : 'warning'));
                        button.addClass('btn-' + (newStatus === 'solved' ? 'success' : 'warning'));
                        button.data('status', newStatus);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred. Please try again.');
                }
            });
        });

        // Delete Data via AJAX
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var userId = $(this).data('userid');
            var valueId = $(this).data('valueid');

            if (confirm('Are you sure you want to delete this data?')) {
                $.ajax({
                    url: '{{ route("admin.user-from-submission-data.delete-value-data", ["userId" => ":userId", "valueId" => ":valueId"]) }}'.replace(':userId', userId).replace(':valueId', valueId),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the row from the table
                            $('#row-' + valueId).remove();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        });
    });
</script>

@endsection
