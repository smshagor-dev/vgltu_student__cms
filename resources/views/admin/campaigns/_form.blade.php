@csrf

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Campaign Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $campaign->title) }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-semibold mb-0">Campaign Fields</label>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-campaign-field">Add Field</button>
            </div>

            <div id="campaign-fields-wrap" class="d-grid gap-2">
                @php
                    $fieldDefinitions = old('field_definitions', $campaign->field_definitions ?? [['label' => '', 'type' => 'text']]);
                @endphp
                @foreach ($fieldDefinitions as $index => $fieldDefinition)
                    <div class="input-group campaign-field-row">
                        <input type="text" name="field_definitions[{{ $index }}][label]" class="form-control @error('field_definitions.' . $index . '.label') is-invalid @enderror" value="{{ $fieldDefinition['label'] ?? '' }}" placeholder="Example: Passport Number" required>
                        <select name="field_definitions[{{ $index }}][type]" class="form-select @error('field_definitions.' . $index . '.type') is-invalid @enderror" style="max-width: 160px;" required>
                            <option value="text" @selected(($fieldDefinition['type'] ?? 'text') === 'text')>Text Input</option>
                            <option value="checkbox" @selected(($fieldDefinition['type'] ?? 'text') === 'checkbox')>Checkbox</option>
                        </select>
                        <button type="button" class="btn btn-outline-danger remove-campaign-field">Remove</button>
                    </div>
                @endforeach
            </div>

            @error('field_definitions')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
            @error('field_definitions.*.label')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
            @error('field_definitions.*.type')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active campaign</label>
        </div>

        <div class="mb-3">
            <label for="notification_title" class="form-label fw-semibold">Notification Title</label>
            <input type="text" name="notification_title" id="notification_title" class="form-control" value="{{ old('notification_title') }}" placeholder="Example: New campaign available">
            @if (!$campaign->exists)
                <div class="form-text">This message will be sent to users after the campaign is created.</div>
            @endif
        </div>

        <div class="mb-4">
            <label for="notification_description" class="form-label fw-semibold">Notification Description</label>
            <textarea name="notification_description" id="notification_description" class="form-control" rows="3" placeholder="Tell students what the campaign is about.">{{ old('notification_description') }}</textarea>
            @if (!$campaign->exists)
                <div class="form-text">If left empty, a default notification message will be used.</div>
            @endif
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Campaign</button>
            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrap = document.getElementById('campaign-fields-wrap');
        const addButton = document.getElementById('add-campaign-field');

        if (!wrap || !addButton) {
            return;
        }

        addButton.addEventListener('click', function () {
            const index = wrap.querySelectorAll('.campaign-field-row').length;
            const row = document.createElement('div');
            row.className = 'input-group campaign-field-row';
            row.innerHTML = '<input type="text" name="field_definitions[' + index + '][label]" class="form-control" placeholder="Example: Passport Number" required><select name="field_definitions[' + index + '][type]" class="form-select" style="max-width: 160px;" required><option value="text">Text Input</option><option value="checkbox">Checkbox</option></select><button type="button" class="btn btn-outline-danger remove-campaign-field">Remove</button>';
            wrap.appendChild(row);
        });

        wrap.addEventListener('click', function (event) {
            if (!event.target.classList.contains('remove-campaign-field')) {
                return;
            }

            if (wrap.querySelectorAll('.campaign-field-row').length === 1) {
                const row = wrap.querySelector('.campaign-field-row');
                const input = row.querySelector('input');
                const select = row.querySelector('select');
                if (input) input.value = '';
                if (select) select.value = 'text';
                return;
            }

            event.target.closest('.campaign-field-row')?.remove();
        });
    });
</script>
