@extends('layouts.admin_app')

@section('content')
<div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 1000px; margin: auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333;">Alumni Network Requests & Students</h2>
        
        <!-- Add New Student Button -->
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="{{ route('students.create') }}" 
               style="padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
               Add New Student
            </a>
        </div>

        <!-- Desktop Student Table -->
        <table class="student-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #007bff; color: white;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Photo</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Name</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Degree, Department, Pass Year</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                            <img src="{{ asset('storage/'.$student->photo_path) }}" width="100" style="border-radius: 5px;">
                        </td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $student->name }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">
                            @php
                                $degrees = is_array($student->degree)
                                    ? $student->degree
                                    : json_decode($student->degree, true) ?? [];

                                $departments = is_array($student->department)
                                    ? $student->department
                                    : json_decode($student->department, true) ?? [];

                                $years = is_array($student->pass_year)
                                    ? $student->pass_year
                                    : json_decode($student->pass_year, true) ?? [];
                            @endphp

                            {!! implode('<br>', array_map(
                                fn($d, $dep, $year) => "$d in $dep $year",
                                $degrees,
                                $departments,
                                $years
                            )) !!}
                        </td>
                        <td style="padding: 10px; border: 1px solid #ddd;">
                            <span style="display:inline-block;padding:6px 10px;border-radius:999px;font-weight:700;color:#fff;background-color:{{ $student->status === 'approved' ? '#16a34a' : '#d97706' }};">
                                {{ ucfirst($student->status) }}
                            </span>
                            <div style="margin-top:8px;color:#666;font-size:12px;">Source: {{ ucfirst(str_replace('_', ' ', $student->source ?? 'admin')) }}</div>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ddd;">
                            @if($student->status === 'pending')
                                <form action="{{ route('students.approve', $student->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="display: inline-block; padding: 5px 10px; background-color: #16a34a; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('students.reject', $student->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="padding: 5px 10px; background-color: #f59e0b; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                        Reject
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('students.edit', $student->id) }}" 
                               style="display: inline-block; padding: 5px 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                               Edit
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Mobile Student Cards -->
        <div class="student-cards">
            @foreach($students as $student)
                <div style="background-color: #fff; margin-bottom: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                    <div style="text-align: center; margin-bottom: 10px;">
                        <img src="{{ asset('storage/'.$student->photo_path) }}" width="100" style="border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <strong>Name:</strong> {{ $student->name }}
                    </div>

                    <div style="margin-bottom: 10px;">
                        <strong>Degree:</strong>
                        {{ implode(', ', is_array($student->degree) ? $student->degree : (json_decode($student->degree, true) ?? [])) }}
                    </div>

                    <div style="margin-bottom: 10px;">
                        <strong>Department:</strong>
                        {{ implode(', ', is_array($student->department) ? $student->department : (json_decode($student->department, true) ?? [])) }}
                    </div>

                    <div style="margin-bottom: 10px;">
                        <strong>Pass Year:</strong>
                        {{ implode(', ', is_array($student->pass_year) ? $student->pass_year : (json_decode($student->pass_year, true) ?? [])) }}
                    </div>

                    <div style="margin-bottom: 10px;">
                        <strong>Status:</strong>

                        <span style="display:inline-block;padding:6px 10px;border-radius:999px;font-weight:700;color:#fff;background-color:{{ $student->status === 'approved' ? '#16a34a' : '#d97706' }};">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>
                    <div style="text-align: center;">
                        @if($student->status === 'pending')
                            <form action="{{ route('students.approve', $student->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" 
                                        style="padding: 5px 10px; background-color: #16a34a; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('students.reject', $student->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" 
                                        style="padding: 5px 10px; background-color: #f59e0b; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    Reject
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('students.edit', $student->id) }}" 
                           style="display: inline-block; padding: 5px 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                           Edit
                        </a>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $students->links() }}
        </div>
    </div>
</div>

<!-- CSS for responsive layout -->
<style>
    /* Hide the table and show cards on mobile */
    @media (max-width: 768px) {
        .student-table {
            display: none;
        }
        .student-cards {
            display: block;
        }
    }

    /* Show the table and hide cards on larger screens */
    @media (min-width: 769px) {
        .student-cards {
            display: none;
        }
    }
</style>

@endsection
