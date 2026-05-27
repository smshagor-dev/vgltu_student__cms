@extends('layouts.app')

@section('content')
<style>
    .campaign-form-page {
        width: min(960px, calc(100% - 32px));
        margin: 32px auto 52px;
    }

    .campaign-form-card {
        padding: 28px;
        border-radius: 28px;
        background: #fff;
        border: 1px solid rgba(35, 23, 38, 0.08);
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
    }

    .campaign-form-badge {
        display: inline-flex;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(215, 89, 139, 0.12);
        color: #bb3e71;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .campaign-form-card h1 {
        margin: 16px 0 10px;
        color: #241726;
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        font-weight: 800;
    }

    .campaign-form-card p {
        margin: 0;
        color: #6f6572;
        line-height: 1.75;
    }

    .campaign-form-field {
        margin-top: 20px;
    }

    .campaign-form-field label {
        display: block;
        margin-bottom: 8px;
        color: #241726;
        font-weight: 700;
    }

    .campaign-form-input {
        min-height: 56px;
        border-radius: 18px;
        border: 1px solid rgba(35, 23, 38, 0.12);
        padding: 14px 16px;
        width: 100%;
    }

    .campaign-choice-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .campaign-choice {
        flex: 1 1 180px;
    }

    .campaign-choice input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .campaign-choice label {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 58px;
        margin: 0;
        padding: 14px 16px;
        border-radius: 18px;
        border: 1px solid rgba(35, 23, 38, 0.12);
        background: #fff;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .campaign-choice label::before {
        content: "";
        width: 18px;
        height: 18px;
        border-radius: 6px;
        border: 2px solid rgba(35, 23, 38, 0.3);
        background: #fff;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .campaign-choice input:checked + label {
        border-color: rgba(187, 62, 113, 0.45);
        background: rgba(215, 89, 139, 0.08);
        box-shadow: 0 12px 24px rgba(215, 89, 139, 0.12);
    }

    .campaign-choice input:checked + label::before {
        border-color: #bb3e71;
        background: linear-gradient(135deg, #f173aa, #bb3e71);
    }

    .campaign-form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .campaign-form-submit,
    .campaign-form-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        padding: 12px 20px;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 800;
    }

    .campaign-form-submit {
        border: 0;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
    }

    .campaign-form-back {
        background: #fff5f8;
        color: #241726;
    }

    @media (max-width: 767px) {
        .campaign-form-page {
            width: calc(100% - 16px);
            margin: 20px auto 36px;
        }

        .campaign-form-card {
            padding: 20px 16px;
            border-radius: 22px;
        }
    }
</style>

<section class="campaign-form-page">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="campaign-form-card">
        <span class="campaign-form-badge">Campaign Form</span>
        <h1>{{ $campaign->title }}</h1>
        <p>
            @if ($submission)
                You already submitted this campaign. Your saved answers are shown below.
            @else
                Complete every field below and submit your response.
            @endif
        </p>

        @if (!$submission)
            <form method="POST" action="{{ route('campaigns.store', $campaign) }}">
                @csrf
        @endif
            @foreach ($fieldDefinitions as $index => $fieldDefinition)
                @php
                    $existingValue = collect($submission->submission ?? [])->firstWhere('field_name', $fieldDefinition['label'])['value'] ?? '';
                @endphp
                <div class="campaign-form-field">
                    <label for="submission_{{ $index }}">{{ $fieldDefinition['label'] }}</label>
                    @if (($fieldDefinition['type'] ?? 'text') === 'checkbox')
                        <div class="campaign-choice-group">
                            <div class="campaign-choice">
                                <input type="radio" id="submission_{{ $index }}_yes" name="submission[{{ $index }}]" value="yes" {{ old('submission.' . $index, $existingValue) === 'yes' ? 'checked' : '' }} {{ $submission ? 'disabled' : 'required' }}>
                                <label for="submission_{{ $index }}_yes">Yes</label>
                            </div>
                            <div class="campaign-choice">
                                <input type="radio" id="submission_{{ $index }}_no" name="submission[{{ $index }}]" value="no" {{ old('submission.' . $index, $existingValue) === 'no' ? 'checked' : '' }} {{ $submission ? 'disabled' : 'required' }}>
                                <label for="submission_{{ $index }}_no">No</label>
                            </div>
                        </div>
                    @else
                        <input type="text" id="submission_{{ $index }}" name="submission[{{ $index }}]" class="campaign-form-input @error('submission.' . $index) is-invalid @enderror" value="{{ old('submission.' . $index, $existingValue) }}" {{ $submission ? 'disabled' : 'required' }}>
                    @endif
                    @error('submission.' . $index)
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div class="campaign-form-actions">
                @if (!$submission)
                    <button type="submit" class="campaign-form-submit">Submit Campaign</button>
                @endif
                <a href="{{ route('campaigns.index') }}" class="campaign-form-back">Back to Campaigns</a>
            </div>
        @if (!$submission)
            </form>
        @endif
    </div>
</section>
@endsection
