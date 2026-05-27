@extends('layouts.admin_app')

@section('content')
    <style>
        form {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        label {
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

    <h1 style="text-align: center;">Create New Description</h1>

    <form action="{{ route('description.store') }}" method="POST">
        @csrf
        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="photo">Photo</option>
            <option value="video">Video</option>
        </select>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="5" required></textarea>

        <button type="submit">Submit</button>
    </form>

</body>
@endsection
