@extends('layouts.admin_app')

@section('content')
<div class="container mt-5">
    <h2>Search Users</h2>
    <form action="{{ route('search') }}" method="GET" class="row g-3">
        <div class="col-md-6">
            <label for="search_criteria" class="form-label">Search By</label>
            <select class="form-select" id="search_criteria" name="search_criteria" required>
                <option value="">Choose...</option>
                <option value="room_number">Room Number</option>
                <option value="full_name">Full Name</option>
                <option value="email">Email</option>
                <option value="mobile_number">Mobile Number</option>
                <option value="country">Country</option>
                <option value="religion">Religion</option>
                <option value="gender">Gender</option>
                <option value="course_type">Course Type</option>
                <option value="department">Department</option>
                <option value="course_year">Course Year</option>
            </select>
        </div>

        <!-- Dynamic Search Fields -->
        <div id="room_number_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="room_number" class="form-label">Room Number</label>
            <input type="text" class="form-control" id="room_number" name="room_number">
        </div>
        <div id="full_name_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name">
        </div>
        <div id="email_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div id="mobile_number_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="text" class="form-control" id="mobile_number" name="mobile_number">
        </div>
        <div id="country_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="country" class="form-label">Country</label>
            <select class="form-select" id="country" name="country">
                <option value="">Choose...</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="India">India</option>
                <option value="Nepal">Nepal</option>
            </select>
        </div>
        <div id="religion_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="religion" class="form-label">Religion</label>
            <select class="form-select" id="religion" name="religion">
                <option value="">Choose...</option>
                <option value="Muslim">Muslim</option>
                <option value="Hindu">Hindu</option>
                <option value="Budhho">Budhho</option>
                <option value="Christian">Christian</option>
            </select>
        </div>
        <div id="gender_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" id="gender" name="gender">
                <option value="">Choose...</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div id="course_type_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="course_type" class="form-label">Course Type</label>
            <select class="form-select" id="course_type" name="course_type">
                <option value="">Choose...</option>
                <option value="Language">Language</option>
                <option value="BSC">BSC</option>
                <option value="MSC">MSC</option>
                <option value="PHD">PHD</option>
            </select>
        </div>
        <div id="department_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="department" class="form-label">Department</label>
            <select class="form-select" id="department" name="department">
                <option value="">Choose...</option>
                <option value="Automobile">Automobile</option>
                <option value="Forestry">Forestry</option>
                <option value="Mechanical">Mechanical</option>
                <option value="Computer Science and Technology">Computer Science and Technology</option>
                <option value="Economics">Economics</option>
                <option value="Landscape Architecture">Landscape Architecture</option>
                <option value="Tourism">Tourism</option>
                <option value="automation of production processes">automation of production processes</option>
                <option value="Life Safety and Legal Relations">Life Safety and Legal Relations</option>
                <option value="Botany and Plant Physiology">Botany and Plant Physiology</option>
                <option value="Hardware and Software">Hardware and Software</option>
            </select>
        </div>
        <div id="course_year_input" class="col-md-6 dynamic-input" style="display:none;">
            <label for="course_year" class="form-label">Course Year</label>
            <select class="form-select" id="course_year" name="course_year">
                <option value="">Choose...</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="Final Year">Final Year</option>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</div>

<!-- jQuery for dynamic field toggling -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dynamic-input').hide(); // Hide all fields initially

        // When a search criteria is selected, show the corresponding field
        $('#search_criteria').on('change', function() {
            var selectedCriteria = $(this).val();
            $('.dynamic-input').hide(); // Hide all fields
            if (selectedCriteria) {
                $('#' + selectedCriteria + '_input').show(); // Show the selected input field
            }
        });
    });
</script>

@endsection
