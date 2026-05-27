@extends('layouts.admin_app')

@section('content')
<div class="container">
    <h1>Custom Fields</h1>
    <a href="{{ route('admin.custom-fields.create') }}" class="btn btn-primary mb-3">Create New Field</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($fields->count())
        <table class="table">
            <thead>
                <tr>
                    <th>Field Label</th>
                    <th>Field Type</th>
                    <th>Field For</th>
                    <th>Options</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fields as $field)
                    @php
                        $options = $field->options;
                    @endphp
                    <tr>
                        <td>{{ $field->field_label }}</td>
                        <td>{{ ucfirst($field->field_type) }}</td>
                        <td>{{ ucfirst($field->target_audience) }}</td>
                        <td>
                            @if($options->count())
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Option Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($options as $option)
                                            <tr>
                                                <td>{{ $option->option_value }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.custom-fields.options.edit', $option->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ route('admin.custom-fields.options.destroy', $option->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No options available</p>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.custom-fields.edit', $field->id) }}" class="btn btn-sm btn-warning">Add new Field</a>
                            <form action="{{ route('admin.custom-fields.destroy', $field->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this field?')">Delete Field</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 d-flex justify-content-center">
            {{ $fields->links() }}
        </div>
    @else
        <p>No custom fields have been created yet.</p>
    @endif
</div>
@endsection
