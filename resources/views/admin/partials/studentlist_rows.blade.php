@foreach ($totalStudentsList as $index => $student)
    <tr>
        <td>{{ $totalStudentsList->firstItem() + $index }}</td>
        <td>{{ $student->room_number }}</td>
        <td>{{ $student->full_name }}</td>
        <td>
            <a href="tel:{{ $student->mobile_number }}">{{ $student->mobile_number }}</a>
        </td>
        <td>{{ $student->country }}</td>
        <td>{{ $student->religion }}</td>
        <td>{{ $student->course_type }}</td>
        <td>{{ $student->department }}</td>
        <td>{{ $student->course_language ?: 'Russian' }}</td>
        <td class="no-print" style="color: {{ $student->medical_status == 'Complete' ? 'green' : ($student->medical_status == 'Not Complete' ? 'red' : 'black') }}">
            {{ $student->medical_status }}
        </td>
        <td class="no-print">
            <div class="admin-actions-inline">
                <a href="{{ route('admin.users.view', ['id' => $student->id]) }}" class="btn btn-sm btn-info">View</a>
                <form action="{{ route('admin.users.delete', ['id' => $student->id]) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                </form>
                <form action="{{ route('admin.forgetPassword', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reset this user\'s password?');" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">Reset Password</button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
