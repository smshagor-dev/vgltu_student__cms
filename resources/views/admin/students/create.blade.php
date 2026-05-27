@extends('layouts.admin_app')

@section('content')
<div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333;">Add New Student</h2>

        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 15px;">
                <label for="name" style="font-weight: bold; color: #333;">Student Name:</label>
                <input type="text" name="name" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>

            <div id="degreeFields">
                <label style="font-weight: bold; color: #333;">Degree:</label>
                <div class="degree-group" style="margin-bottom: 15px; display: flex; gap: 10px;">
                    <input type="text" name="degree[]" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <button type="button" onclick="addField('degreeFields', 'degree[]')" style="padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">+</button>
                </div>
            </div>
            
            <div id="departmentFields">
                <label style="font-weight: bold; color: #333;">Department:</label>
                <div class="department-group" style="margin-bottom: 15px; display: flex; gap: 10px;">
                    <input type="text" name="department[]" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <button type="button" onclick="addField('departmentFields', 'department[]')" style="padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">+</button>
                </div>
            </div>
            
            <div id="passYearFields">
                <label style="font-weight: bold; color: #333;">Pass Year:</label>
                <div class="pass-year-group" style="margin-bottom: 15px; display: flex; gap: 10px;">
                    <input type="text" name="pass_year[]" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <button type="button" onclick="addField('passYearFields', 'pass_year[]')" style="padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">+</button>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="photo" style="font-weight: bold; color: #333;">Upload Photo:</label>
                <input type="file" name="photo" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>
            
            <button type="submit" style="width: 100%; background-color: #28a745; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer;">
                Submit
            </button>
        </form>
    </div>
</div>

<script>
    function addField(containerId, fieldName) {
        let container = document.getElementById(containerId);
        let div = document.createElement("div");
        div.style.display = "flex";
        div.style.gap = "10px";
        div.style.marginBottom = "10px";

        let input = document.createElement("input");
        input.type = "text";
        input.name = fieldName;
        input.style.flex = "1";
        input.style.padding = "10px";
        input.style.border = "1px solid #ddd";
        input.style.borderRadius = "5px";
        input.required = true;

        let removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.textContent = "-";
        removeButton.style.padding = "10px";
        removeButton.style.backgroundColor = "#dc3545";
        removeButton.style.color = "white";
        removeButton.style.border = "none";
        removeButton.style.borderRadius = "5px";
        removeButton.style.cursor = "pointer";
        removeButton.onclick = function () {
            container.removeChild(div);
        };

        div.appendChild(input);
        div.appendChild(removeButton);
        container.appendChild(div);
    }
</script>
@endsection
