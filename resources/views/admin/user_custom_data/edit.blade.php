@extends('layouts.admin_app')

@section('content')
<div class="container">
    <h1>Edit Data for {{ $user->name }}</h1>

    <form action="{{ route('admin.user-custom-data.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @foreach ($submittedData as $data)
            <div class="mb-3">
                <label for="field_{{ $data->customField->id }}" class="form-label">{{ $data->customField->field_label }}</label>

                {{-- Check if the description exists and show it --}}
                @if (!empty($data->customField->description))
                    <p class="text-muted">{{ $data->customField->description }}</p>
                @endif

                {{-- Text Field --}}
                @if ($data->customField->field_type === 'text')
                    <input 
                        type="text" 
                        name="field_{{ $data->customField->id }}" 
                        class="form-control" 
                        value="{{ $data->value }}" 
                        required>

                {{-- Image Field --}}
                @elseif ($data->customField->field_type === 'image')
                    <input 
                        type="file" 
                        name="field_{{ $data->customField->id }}" 
                        class="form-control">
                    @if ($data->value)
                        <img src="{{ asset('storage/' . $data->value) }}" 
                             alt="{{ $data->customField->field_label }}" 
                             class="img-fluid mt-2" 
                             style="max-width: 100px;">
                    @endif

                {{-- Textarea Field --}}
                @elseif ($data->customField->field_type === 'textarea')
                    <textarea 
                        name="field_{{ $data->customField->id }}" 
                        class="form-control" 
                        rows="4">{{ $data->value }}</textarea>

                {{-- Select Field --}}
                @elseif ($data->customField->field_type === 'select')
                    <select 
                        name="field_{{ $data->customField->id }}" 
                        class="form-select">
                        @foreach (json_decode($data->customField->options, true) as $option)
                            <option value="{{ $option }}" 
                                    {{ $data->value == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>

                {{-- Checkbox Field --}}
                @elseif ($data->customField->field_type === 'checkbox')
                    @foreach (json_decode($data->customField->options, true) as $option)
                        <div class="form-check">
                            <input 
                                type="checkbox" 
                                name="field_{{ $data->customField->id }}[]" 
                                value="{{ $option }}" 
                                class="form-check-input" 
                                id="checkbox_{{ $data->customField->id }}_{{ $loop->index }}" 
                                {{ in_array($option, json_decode($data->value, true) ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" 
                                   for="checkbox_{{ $data->customField->id }}_{{ $loop->index }}">
                                {{ $option }}
                            </label>
                        </div>
                    @endforeach

                {{-- Radio Field --}}
                @elseif ($data->customField->field_type === 'radio')
                    @foreach (json_decode($data->customField->options, true) as $option)
                        <div class="form-check">
                            <input 
                                type="radio" 
                                name="field_{{ $data->customField->id }}" 
                                value="{{ $option }}" 
                                class="form-check-input" 
                                id="radio_{{ $data->customField->id }}_{{ $loop->index }}" 
                                {{ $data->value == $option ? 'checked' : '' }}>
                            <label class="form-check-label" 
                                   for="radio_{{ $data->customField->id }}_{{ $loop->index }}">
                                {{ $option }}
                            </label>
                        </div>
                    @endforeach

                {{-- Multiple Choice Field --}}
                @elseif ($data->customField->field_type === 'multiple_choice')
                    @php
                        $selectedOptions = json_decode($data->value, true) ?? [];
                    @endphp
                    @foreach (json_decode($data->customField->options, true) as $option)
                        <div class="form-check">
                            <input 
                                type="checkbox" 
                                name="field_{{ $data->customField->id }}[]" 
                                value="{{ $option }}" 
                                class="form-check-input" 
                                id="multiple_choice_{{ $data->customField->id }}_{{ $loop->index }}" 
                                {{ in_array($option, $selectedOptions) ? 'checked' : '' }}>
                            <label class="form-check-label" 
                                   for="multiple_choice_{{ $data->customField->id }}_{{ $loop->index }}">
                                {{ $option }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
