@extends('layouts.admin_app')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('admin.custom-fields.store') }}" style="max-width: 600px; margin: 50px auto; background-color: #fff; padding: 20px 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        @csrf
        
        <!-- Field Name -->
        <div style="margin-bottom: 20px;">
            <label for="field_label" style="display: block; font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">Name Of Field:</label>
            <input type="text" id="field_label" name="field_label" value="{{ old('field_label') }}" required style="width: 100%; padding: 10px 15px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px;">
            @error('field_label')
                <span style="color: red; font-size: 13px;">{{ $message }}</span>
            @enderror
        </div>
        
        <!-- Field Type -->
        <div style="margin-bottom: 20px;">
            <label for="field_type" style="display: block; font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">Type of Field:</label>
            <select id="field_type" name="field_type" required style="width: 100%; padding: 10px 15px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="" disabled {{ old('field_type') ? '' : 'selected' }}>Select Option</option>    
                <!--<option value="text" {{ old('field_type') == 'text' ? 'selected' : '' }}>Text</option>-->
                <!--<option value="image" {{ old('field_type') == 'image' ? 'selected' : '' }}>Image</option>-->
                <option value="multiple_choice" {{ old('field_type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
            </select>
            @error('field_type')
                <span style="color: red; font-size: 13px;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Target Audience -->
        <div style="margin-bottom: 20px;">
            <label for="target_audience" style="display: block; font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">Category:</label>
            <select id="target_audience" name="target_audience" required style="width: 100%; padding: 10px 15px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="" disabled {{ old('target_audience') ? '' : 'selected' }}>Select Option</option>
                <option value="room" {{ old('target_audience') == 'room' ? 'selected' : '' }}>Problem By Room</option>
                <option value="student" {{ old('target_audience') == 'student' ? 'selected' : '' }}>For All Students</option>
            </select>
            @error('target_audience')
                <span style="color: red; font-size: 13px;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Multiple Choice Options (Hidden Initially) -->
        <div id="multiple-choice-options" style="display: {{ old('field_type') == 'multiple_choice' ? 'block' : 'none' }}; margin-bottom: 20px;">
            <label style="display: block; font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">Input Options:</label>
            <div id="options-container">
                @if(old('options'))
                    @foreach(old('options') as $index => $option)
                        <div class="option-item" style="display: flex; align-items: center; margin-bottom: 10px;">
                            <input type="text" name="options[]" value="{{ $option }}" required placeholder="Option {{ $index + 1 }}" style="flex: 1; padding: 10px 15px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px; margin-right: 10px;">
                            <button type="button" onclick="removeOption(this)" style="padding: 10px 15px; font-size: 14px; color: #fff; background-color: #dc3545; border: none; border-radius: 5px; cursor: pointer;">Remove</button>
                        </div>
                    @endforeach
                @else
                    <div class="option-item" style="display: flex; align-items: center; margin-bottom: 10px;">
                        <input type="text" name="options[]" required placeholder="Option 1" style="flex: 1; padding: 10px 15px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px; margin-right: 10px;">
                    </div>
                @endif
            </div>
            <button type="button" onclick="addOption()" style="padding: 10px 15px; font-size: 14px; color: #fff; background-color: #6c757d; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;">Add More Options</button>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="notification_title" style="display: block; font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">Notification Title:</label>
            <input type="text" id="notification_title" name="notification_title" value="{{ old('notification_title') }}" placeholder="Example: New form available" style="width: 100%; padding: 10px 15px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="notification_description" style="display: block; font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px;">Notification Description:</label>
            <textarea id="notification_description" name="notification_description" rows="3" placeholder="Tell students what they need to do." style="width: 100%; padding: 10px 15px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px;">{{ old('notification_description') }}</textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" style="padding: 10px 20px; font-size: 14px; color: #fff; background-color: #007bff; border: none; border-radius: 5px; cursor: pointer;">Save</button>
    </form>
</div>

<script>
    document.getElementById('field_type').addEventListener('change', function () {
        document.getElementById('multiple-choice-options').style.display = this.value === 'multiple_choice' ? 'block' : 'none';
    });

    function addOption() {
    const container = document.getElementById('options-container');
    const optionItem = document.createElement('div');
    optionItem.className = 'option-item';
    optionItem.style.display = 'flex';
    optionItem.style.alignItems = 'center';
    optionItem.style.marginBottom = '10px';

    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'options[]';
    newInput.required = true;
    newInput.placeholder = 'Option ' + (container.children.length + 1);
    newInput.style.flex = '1';
    newInput.style.padding = '10px 15px';
    newInput.style.fontSize = '14px';
    newInput.style.border = '1px solid #ccc';
    newInput.style.borderRadius = '5px';
    newInput.style.marginRight = '10px';

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.textContent = 'Remove';
    removeBtn.style.padding = '10px 15px';
    removeBtn.style.fontSize = '14px';
    removeBtn.style.color = '#fff';
    removeBtn.style.backgroundColor = '#dc3545';
    removeBtn.style.border = 'none';
    removeBtn.style.borderRadius = '5px';
    removeBtn.style.cursor = 'pointer';
    removeBtn.onclick = function () {
        container.removeChild(optionItem);
    };

    optionItem.appendChild(newInput);
    optionItem.appendChild(removeBtn);
    container.appendChild(optionItem);
}

</script>
@endsection
