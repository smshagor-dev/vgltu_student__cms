@extends('layouts.admin_app')

@section('content')
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        button {
            padding: 5px 10px;
            margin: 5px;
            cursor: pointer;
        }
        .add-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

    <h1 style="text-align: center;">Photo & Video Descriptions</h1>

    <a href="{{ route('description.create') }}">
        <button class="add-button">Add New Description</button>
    </a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($descriptions as $description)
            <tr>
                <td>{{ $description->id }}</td>
                <td>{{ ucfirst($description->type) }}</td>
                <td>{{ $description->description }}</td>
                <td>
                    <a href="{{ route('description.edit', $description->id) }}">
                        <button>Edit</button>
                    </a>
                    <form action="{{ route('description.delete', $description->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background-color:red;color:white;">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 d-flex justify-content-center">
        {{ $descriptions->links() }}
    </div>

</body>
@endsection
