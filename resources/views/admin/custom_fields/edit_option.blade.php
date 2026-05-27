@extends('layouts.admin_app')

@section('content')
<div class="container">
        <!-- Back Button -->
    <button onclick="goBack()" style="margin-bottom: 15px; padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
        ← Go Back
    </button>
    
    <script>
    function goBack() {
        window.history.back();
    }
    </script>
    <h1>Edit Option for: {{ $option->customField->field_label }}</h1> <!-- Display Field Label -->

    <form action="{{ route('admin.custom-fields.options.update', $option->id) }}" method="POST">
        @csrf
        @method('POST') <!-- Use PUT for updating -->

        <div class="form-group">
            <label for="option_value">Option Value</label>
            <input type="text" id="option_value" name="option_value" class="form-control" value="{{ old('option_value', $option->option_value) }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save changes</button>
    </form>
</div>
@endsection
