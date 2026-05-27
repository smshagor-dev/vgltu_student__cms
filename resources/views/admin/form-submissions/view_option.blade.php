@extends('layouts.admin_app')

@section('content')

<div style="max-width: 100%; padding: 15px; font-family: Arial, sans-serif;">

    <h2 style="margin-bottom: 15px; font-size: 1.6rem; text-align: center; color: #333;">Submited Data</h2>

    <div style="margin-bottom: 10px; text-align: center; font-weight: bold; color: #007bff;">
        <h1>Total Users: {{ method_exists($userFieldData, 'total') ? $userFieldData->total() : $userFieldData->count() }}</h1>
    </div>
    
    <!-- Back Button -->
    <button onclick="goBack()" style="margin-bottom: 15px; padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
        ← Go Back
    </button>
<center>
    <div style="display: flex; justify-content: center; padding: 15px;">
    <div style="overflow-x: auto; width: 100%;">
        <table style="width: 50%; border-collapse: collapse; font-size: 13px; color: #444; margin: auto;">
            <thead style="background-color: #007bff; color: #fff; text-align: left;">
                <tr>
                    <th style="padding: 8px; border: 1px solid #ddd;">Room No</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Full Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userFieldData as $user)
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 6px; border: 1px solid #ddd;">{{ $user->room_number }}</td>
                        <td style="padding: 6px; border: 1px solid #ddd;">{{ $user->full_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
</center>

<div class="mt-4 d-flex justify-content-center">
    {{ $userFieldData->links() }}
</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>

<style>
    /* Responsive Design */
    @media screen and (max-width: 768px) {
        table {
            width: 100% !important; /* Full width on mobile */
        }
    }
</style>

@endsection
