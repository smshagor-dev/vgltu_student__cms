@extends('layouts.app')

@section('content') 
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Form Header -->
            <div class="text-center mb-4">
                <h1 class="text-primary" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                    <u>Submit Problem</u>
                </h1>
            </div>

            @if($unfilledFields->isNotEmpty())
                <form method="POST" action="{{ route('user-fields.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow-sm p-4 rounded-lg">
                        @foreach ($unfilledFields as $field)
                            <div class="mb-4">
                                <div class="text-center">
                                    <label for="field_{{ $field->id }}" class="form-label fw-bold text-info" style="font-size: 1.3rem;">
                                        <h3>{{ e($field->field_label) }}</h3>
                                    </label>
                                </div>

                                @if ($field->field_type === 'text')
                                    <input 
                                        type="text" 
                                        name="field_{{ $field->id }}" 
                                        class="form-control shadow-sm border-info" 
                                        id="field_{{ $field->id }}" 
                                        placeholder="Enter {{ e($field->field_label) }}" 
                                        required
                                    >
                                @elseif ($field->field_type === 'image')
                                    <input 
                                        type="file" 
                                        name="field_{{ $field->id }}" 
                                        class="form-control shadow-sm border-info" 
                                        id="field_{{ $field->id }}" 
                                        accept="image/jpeg, image/png, image/jpg" 
                                        required
                                    >
                                @elseif ($field->field_type === 'multiple_choice')
                                    @if ($field->options && $field->options->isNotEmpty())
                                        @foreach ($field->options as $option)
                                            <div class="form-check mb-3">
                                                <input 
                                                    type="checkbox" 
                                                    name="field_{{ $field->id }}[]" 
                                                    value="{{ e($option->option_value) }}" 
                                                    id="option_{{ $field->id }}_{{ $loop->index }}" 
                                                    class="form-check-input shadow-sm"
                                                >
                                                <label class="form-check-label" for="option_{{ $field->id }}_{{ $loop->index }}">
                                                    {{ e($option->option_value) }}
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <textarea 
                                                    name="description_{{ $field->id }}[{{ e($option->option_value) }}]" 
                                                    class="form-control description-field shadow-sm"
                                                    placeholder="Add a description for {{ e($option->option_value) }}"
                                                    style="display: none;"
                                                ></textarea>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No options available</p>
                                    @endif
                                @endif
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-lg">Submit</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-info text-center shadow-sm" role="alert">
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
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("input[type='checkbox']").forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            let fieldIdMatch = this.name.match(/field_(\d+)/);
            if (!fieldIdMatch) return;

            let fieldId = fieldIdMatch[1];
            let descriptionField = document.querySelector(`textarea[name="description_${fieldId}[${CSS.escape(this.value)}]"]`);

            if (descriptionField) {
                if (this.checked) {
                    descriptionField.style.display = "block";
                    descriptionField.setAttribute("required", "required");
                } else {
                    descriptionField.style.display = "none";
                    descriptionField.removeAttribute("required");
                }
            }
        });
    });

    // Show descriptions for already checked boxes when the page loads
    document.querySelectorAll("input[type='checkbox']:checked").forEach(function (checkbox) {
        let fieldIdMatch = checkbox.name.match(/field_(\d+)/);
        if (!fieldIdMatch) return;

        let fieldId = fieldIdMatch[1];
        let descriptionField = document.querySelector(`textarea[name="description_${fieldId}[${CSS.escape(checkbox.value)}]"]`);

        if (descriptionField) {
            descriptionField.style.display = "block";
            descriptionField.setAttribute("required", "required");
        }
    });
});
</script>

@endsection

