@extends('layouts.app')

@section('content')
<div class="container">
    <center><h1><u>Add Exiting Data</u></h1></center>
    <button onclick="goBack()" style="margin-bottom: 15px; padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
        ← Go Back
    </button>

    @if($fields->isNotEmpty())
            @foreach ($fields as $field)
                @php
                    $fieldOptions = $field->getRelation('options') ?? collect();
                @endphp
                <form method="POST" action="{{ route('user-fields.existing-store') }}" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <input type="hidden" name="submitted_field_id" value="{{ $field->id }}">
                    <div class="mb-4">
                    <center>
                        <label for="field_{{ $field->id }}" class="form-label fw-bold">
                            <h3><b style="color: blue;">{{ e($field->field_label) }}</b></h3>
                        </label>
                    </center>

                    @if ($field->field_type === 'text')
                        <input 
                            type="text" 
                            name="field_{{ $field->id }}" 
                            class="form-control" 
                            id="field_{{ $field->id }}" 
                            placeholder="Enter {{ e($field->field_label) }}" 
                            required
                        >
                    @elseif ($field->field_type === 'image')
                        <input 
                            type="file" 
                            name="field_{{ $field->id }}" 
                            class="form-control" 
                            id="field_{{ $field->id }}" 
                            accept="image/jpeg, image/png, image/jpg" 
                            required
                        >
                   @elseif ($field->field_type === 'multiple_choice')
                        @if ($fieldOptions->isNotEmpty())
                            @foreach ($fieldOptions as $option)
                                <div class="form-check mb-2">
                                    <input 
                                        type="checkbox" 
                                        name="field_{{ $field->id }}[]" 
                                        value="{{ e($option->option_value) }}" 
                                        id="option_{{ $field->id }}_{{ $loop->index }}" 
                                        class="form-check-input"
                                        data-index="{{ $loop->index }}"
                                        @if(in_array($option->id, $filledFieldIds)) checked @endif
                                        @if(in_array($option->id, $filledFieldIds)) disabled @endif
                                    >
                                    <label class="form-check-label" for="option_{{ $field->id }}_{{ $loop->index }}">
                                        {{ e($option->option_value) }}
                                    </label>

                                    <!-- Hidden description input -->
                                    <div class="mb-3 description-container" id="description-container-{{ $field->id }}_{{ $loop->index }}" 
                                        style="display: {{ isset($filledDescriptions[$option->id]) ? 'block' : 'none' }};">
                                        
                                        <textarea 
                                            name="description_{{ $field->id }}[{{ e($option->option_value) }}]" 
                                            class="form-control description-field"
                                            placeholder="Add a description for {{ e($option->option_value) }}"
                                            {{ isset($filledDescriptions[$option->id]) ? '' : 'style=display:none;' }}
                                        >{{ isset($filledDescriptions[$option->id]) ? e($filledDescriptions[$option->id]) : '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No options available</p>
                        @endif
                    @endif
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5">Submit This Section</button>
                    </div>
                </form>
            @endforeach
    @else
        <div class="alert alert-info text-center" role="alert">
            <p>
                @if($submittedData->isNotEmpty())
                    The form has already been submitted for your room.
                @else
                    All fields have already been filled out.
                @endif
            </p>
        </div>
    @endif
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Loop through each checkbox and add an event listener
    document.querySelectorAll("input[type='checkbox']").forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            // Get the field and option IDs from the checkbox
            let fieldId = this.name.match(/\d+/)[0];  // Extract the field ID
            let index = this.getAttribute("data-index"); // Get the data-index attribute

            // Find the corresponding description container and textarea
            let descriptionContainer = document.querySelector(`#description-container-${fieldId}_${index}`);
            let textarea = descriptionContainer ? descriptionContainer.querySelector("textarea") : null;

            // Show or hide the description field based on the checkbox state
            if (this.checked) {
                if (descriptionContainer) {
                    descriptionContainer.style.display = "block"; // Show description
                }
                if (textarea) {
                    textarea.style.display = "block"; // Show textarea
                    textarea.setAttribute("required", "required"); // Make description required
                }
            } else {
                if (descriptionContainer) {
                    descriptionContainer.style.display = "none"; // Hide description
                }
                if (textarea) {
                    textarea.style.display = "none"; // Hide textarea
                    textarea.removeAttribute("required"); // Remove required attribute
                }
            }
        });
    });

    // Pre-fill the description if the checkbox is checked by default
    document.querySelectorAll("input[type='checkbox']:checked").forEach(function (checkbox) {
        let fieldId = checkbox.name.match(/\d+/)[0];  // Extract the field ID
        let index = checkbox.getAttribute("data-index"); // Get the data-index attribute
        
        let descriptionContainer = document.querySelector(`#description-container-${fieldId}_${index}`);
        let textarea = descriptionContainer ? descriptionContainer.querySelector("textarea") : null;
        
        if (descriptionContainer) {
            descriptionContainer.style.display = "block"; // Show description
        }
        if (textarea) {
            textarea.style.display = "block"; // Show textarea
            textarea.setAttribute("required", "required"); // Make description required
        }
    });
});

</script>

<script>
    function goBack() {
        window.history.back();
    }
</script>

@endsection
