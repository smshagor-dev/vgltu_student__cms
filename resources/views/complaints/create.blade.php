@extends('layouts.app')

@section('content')
<style>
    .complaint-create-page {
        padding: 32px 0 72px;
    }

    .complaint-create-shell {
        max-width: 980px;
        margin: 0 auto;
    }

    .complaint-create-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 34px;
        background:
            radial-gradient(circle at top right, rgba(241, 115, 170, 0.2), transparent 30%),
            linear-gradient(135deg, #241726 0%, #4c2a41 55%, #bb3e71 100%);
        color: #fff;
        box-shadow: 0 26px 60px rgba(36, 23, 38, 0.18);
    }

    .complaint-create-hero::after {
        content: "";
        position: absolute;
        right: -50px;
        bottom: -70px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .complaint-create-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .complaint-create-hero h1 {
        margin: 18px 0 12px;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.08;
    }

    .complaint-create-hero p {
        max-width: 700px;
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.8;
    }

    .complaint-create-hero__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .complaint-create-hero__meta-item {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        font-weight: 600;
    }

    .complaint-create-hero__meta-item i {
        color: #ffd8e8;
    }

    .complaint-create-card {
        margin-top: 28px;
        background: #fff;
        border: 1px solid rgba(76, 42, 65, 0.08);
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 22px 60px rgba(76, 42, 65, 0.1);
    }

    .complaint-create-card__head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .complaint-create-card__head h2 {
        margin: 0 0 8px;
        color: #241726;
        font-size: 1.5rem;
        font-weight: 800;
    }

    .complaint-create-card__head p {
        margin: 0;
        color: #6f6572;
        line-height: 1.7;
    }

    .complaint-create-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        min-height: 58px;
        padding: 10px;
        border-radius: 18px;
        background: #fff5f8;
        color: #bb3e71;
        font-size: 1.05rem;
        font-weight: 800;
    }

    .complaint-create-alert {
        margin-bottom: 20px;
        border: 0;
        border-radius: 18px;
        padding: 16px 18px;
        box-shadow: 0 16px 40px rgba(36, 23, 38, 0.08);
    }

    .complaint-create-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .complaint-create-field {
        margin-bottom: 20px;
    }

    .complaint-create-field--full {
        grid-column: 1 / -1;
    }

    .complaint-create-field label {
        display: block;
        margin-bottom: 8px;
        color: #241726;
        font-weight: 700;
    }

    .complaint-create-control,
    .complaint-create-control.form-control {
        min-height: 54px;
        border-radius: 16px;
        border: 1px solid rgba(76, 42, 65, 0.14);
        box-shadow: none;
    }

    .complaint-create-control:focus,
    .complaint-create-control.form-control:focus {
        border-color: rgba(187, 62, 113, 0.4);
        box-shadow: 0 0 0 0.2rem rgba(187, 62, 113, 0.12);
    }

    .complaint-create-control[disabled] {
        background: #f8f4f7;
        color: #6f6572;
        opacity: 1;
    }

    .complaint-create-field textarea {
        min-height: 170px;
        padding-top: 14px;
        resize: vertical;
    }

    .complaint-create-help {
        margin-top: 8px;
        color: #7a6d79;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .complaint-create-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
    }

    .complaint-create-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 999px;
        border: 1px solid rgba(76, 42, 65, 0.14);
        background: #fff;
        color: #241726;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 12px 24px rgba(76, 42, 65, 0.08);
    }

    .complaint-create-submit {
        border: 0;
        border-radius: 999px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 14px 30px rgba(187, 62, 113, 0.24);
    }

    .complaint-create-submit:hover,
    .complaint-create-submit:focus {
        color: #fff;
    }

    @media (max-width: 991px) {
        .complaint-create-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .complaint-create-page {
            padding: 20px 0 56px;
        }

        .complaint-create-hero,
        .complaint-create-card {
            padding: 22px;
            border-radius: 22px;
        }

        .complaint-create-card__head,
        .complaint-create-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .complaint-create-back,
        .complaint-create-submit {
            justify-content: center;
            width: 100%;
        }
    }
</style>

<div class="container complaint-create-page">
    <div class="complaint-create-shell">
        <section class="complaint-create-hero">
            <span class="complaint-create-hero__eyebrow">
                <i class="fas fa-comment-dots"></i>
                Student Support
            </span>
            <h1>Submit a Complaint</h1>
            <p>Share your issue clearly so the support team can review it faster. Your current submit flow, field names, and validation remain unchanged.</p>
            <div class="complaint-create-hero__meta">
                <div class="complaint-create-hero__meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ Auth::user()->full_name }}</span>
                </div>
                <div class="complaint-create-hero__meta-item">
                    <i class="fas fa-door-open"></i>
                    <span>Room {{ Auth::user()->room_number ?? 'N/A' }}</span>
                </div>
                <div class="complaint-create-hero__meta-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>{{ Auth::user()->mobile_number ?? 'N/A' }}</span>
                </div>
            </div>
        </section>

        <section class="complaint-create-card">
            <div class="complaint-create-card__head">
                <div>
                    <h2>Complaint Details</h2>
                    <p>Provide a short subject and explain the problem in the description box. The more specific the message, the easier it is to track and resolve.</p>
                </div>
                <div class="complaint-create-pill">2</div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger complaint-create-alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('complaints.store') }}">
                @csrf

                <div class="complaint-create-grid">
                    <div class="complaint-create-field">
                        <label for="complaint_full_name">Full Name</label>
                        <input type="text" id="complaint_full_name" class="form-control complaint-create-control" value="{{ Auth::user()->full_name }}" disabled>
                    </div>

                    <div class="complaint-create-field">
                        <label for="complaint_room_number">Room Number</label>
                        <input type="text" id="complaint_room_number" class="form-control complaint-create-control" value="{{ Auth::user()->room_number }}" disabled>
                    </div>

                    <div class="complaint-create-field">
                        <label for="complaint_mobile_number">Mobile Number</label>
                        <input type="text" id="complaint_mobile_number" class="form-control complaint-create-control" value="{{ Auth::user()->mobile_number }}" disabled>
                    </div>
                </div>

                <div class="complaint-create-field">
                    <label for="subject">Subject</label>
                    <input
                        type="text"
                        name="subject"
                        id="subject"
                        class="form-control complaint-create-control"
                        value="{{ old('subject') }}"
                        placeholder="Short title for the complaint"
                        required
                    >
                    <div class="complaint-create-help">Example: Water supply issue, room cleaning problem, internet not working.</div>
                </div>

                <div class="complaint-create-field">
                    <label for="description">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        class="form-control complaint-create-control"
                        placeholder="Explain the issue in detail..."
                        required
                    >{{ old('description') }}</textarea>
                    <div class="complaint-create-help">Include what happened, where it happened, and any urgent details that can help the team respond properly.</div>
                </div>

                <div class="complaint-create-actions">
                    <a href="javascript:history.back()" class="complaint-create-back">
                        <i class="fas fa-arrow-left"></i>
                        Go Back
                    </a>
                    <button type="submit" class="complaint-create-submit">Submit Complaint</button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
