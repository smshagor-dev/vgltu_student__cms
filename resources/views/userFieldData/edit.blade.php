@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <center><h1 class="mb-4">Edit Submitted Data for Field {{ $userFieldData->customField->field_label }}</h1></center>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('user-field-data.update', $userFieldData->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <div class="card p-4 shadow-sm">
            <!-- Field Label -->
            <div class="mb-3">
                <center>
                    <h3>
                        <div class="form-control bg-light">
                            <strong>Field Name:</strong> {{ $userFieldData->customField->field_label }}
                        </div>
                    </h3>
                </center>
            </div>

            <!-- Submitted Data -->
            <div class="mb-3">
                <label class="form-label"><strong>Submitted Data:</strong></label>

                @if($userFieldData->customField->field_type === 'text' || $userFieldData->customField->field_type === 'textarea')
                    <!-- Display the submitted text data as editable -->
                    <input 
                        type="text" 
                        name="field_value" 
                        class="form-control" 
                        value="{{ old('field_value', $userFieldData->value) }}">

                @elseif($userFieldData->customField->field_type === 'image')
                    <!-- Display the uploaded image with the option to upload a new one -->
                    <input type="file" name="field_value" class="form-control">
                    
                    @if($userFieldData->value)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $userFieldData->value) }}" 
                                 alt="{{ $userFieldData->customField->field_label }}" 
                                 class="img-fluid rounded shadow-sm" 
                                 style="max-width: 150px;">
                            <p class="text-muted">Leave empty if you don't want to change the image.</p>
                        </div>
                    @endif

                @elseif($userFieldData->customField->field_type === 'multiple_choice')
                    @php
                        $selectedOptions = explode(',', $userFieldData->value ?? ''); // Selected values from user_field_data
                        $descriptions = explode('|', $userFieldData->description ?? ''); // Descriptions (split using '|')
                    @endphp

                    @foreach($selectedOptions as $index => $option)
                        <div class="mb-3">
                            <div class="form-check">
                                <!-- Display the selected options as checkboxes that can be selected/deselected -->
                                <input 
                                    type="checkbox" 
                                    name="field_value[]" 
                                    value="{{ $option }}" 
                                    class="form-check-input"
                                    id="option_{{ $index }}"
                                    checked
                                >
                                <label class="form-check-label" for="option_{{ $index }}">
                                    {{ $option }}
                                </label>
                            </div>

                            <!-- Allow user to edit the description for the selected option -->
                            <textarea 
                                name="description[{{ $option }}]" 
                                class="form-control mt-2" 
                                placeholder="Enter description for {{ $option }}"
                            >{{ old('description.' . $option, $descriptions[$index] ?? '') }}</textarea>
                        </div>
                    @endforeach
                @endif
            </div>

            <button type="submit" class="btn btn-success w-100">Update</button>
        </div>
    </form>
</div>

@endsection
