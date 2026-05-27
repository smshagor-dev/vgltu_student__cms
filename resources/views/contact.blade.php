@extends('layouts.app')

@section('content')
<style>
    .contact-page {
        width: min(1120px, calc(100% - 32px));
        margin: 28px auto 52px;
        display: grid;
        gap: 24px;
    }

    .contact-page__hero {
        position: relative;
        overflow: hidden;
        padding: 34px;
        border-radius: 34px;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 24%),
            linear-gradient(135deg, #241726 0%, #bb3e71 62%, #f09a62 100%);
        color: #fff;
        box-shadow: 0 28px 56px rgba(76, 42, 65, 0.18);
    }

    .contact-page__hero h1 {
        margin: 0.8rem 0 0.9rem;
        font-size: clamp(2rem, 4vw, 3.5rem);
        font-weight: 800;
        line-height: 1.03;
        color: #fff;
    }

    .contact-page__hero p {
        max-width: 62ch;
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.85;
    }

    .contact-page__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.6rem 0.95rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.14);
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .contact-page__grid {
        display: grid;
        grid-template-columns: minmax(0, 0.86fr) minmax(0, 1.14fr);
        gap: 24px;
    }

    .contact-page__panel,
    .contact-page__form-shell {
        padding: 26px;
        border-radius: 30px;
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
    }

    .contact-page__panel h3,
    .contact-page__form-shell h3 {
        margin: 0 0 0.75rem;
        color: #241726;
        font-weight: 800;
    }

    .contact-page__panel p,
    .contact-page__meta-copy {
        margin: 0;
        color: #6f6572;
        line-height: 1.8;
    }

    .contact-page__meta {
        display: grid;
        gap: 14px;
        margin-top: 22px;
    }

    .contact-page__meta-card {
        padding: 16px 18px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid rgba(35, 23, 38, 0.06);
        box-shadow: 0 10px 22px rgba(50, 32, 48, 0.05);
    }

    .contact-page__meta-card small {
        display: block;
        margin-bottom: 6px;
        color: #bb3e71;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .contact-page__meta-card strong,
    .contact-page__meta-card a {
        color: #241726;
        font-size: 0.98rem;
        font-weight: 700;
        text-decoration: none;
    }

    .contact-page__form {
        display: grid;
        gap: 16px;
    }

    .contact-page__form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .contact-page__field {
        display: grid;
        gap: 8px;
    }

    .contact-page__field--full {
        grid-column: 1 / -1;
    }

    .contact-page__field label {
        color: #241726;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .contact-page__field input,
    .contact-page__field textarea {
        width: 100%;
        border: 1px solid rgba(35, 23, 38, 0.12);
        border-radius: 16px;
        padding: 0.92rem 1rem;
        background: #fff;
        color: #241726;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .contact-page__field textarea {
        min-height: 180px;
        resize: vertical;
    }

    .contact-page__field input:focus,
    .contact-page__field textarea:focus {
        border-color: rgba(187, 62, 113, 0.54);
        box-shadow: 0 0 0 4px rgba(187, 62, 113, 0.12);
    }

    .contact-page__alert {
        padding: 0.95rem 1rem;
        border-radius: 18px;
        font-size: 0.92rem;
        line-height: 1.7;
    }

    .contact-page__alert--success {
        background: rgba(22, 163, 74, 0.12);
        color: #166534;
        border: 1px solid rgba(22, 163, 74, 0.16);
    }

    .contact-page__alert--error {
        background: rgba(220, 38, 38, 0.08);
        color: #991b1b;
        border: 1px solid rgba(220, 38, 38, 0.14);
    }

    .contact-page__error {
        color: #b91c1c;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .contact-page__actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .contact-page__note {
        color: #6f6572;
        font-size: 0.88rem;
        line-height: 1.7;
    }

    .contact-page__submit {
        border: 0;
        min-height: 50px;
        padding: 0.95rem 1.6rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
        font-weight: 800;
        box-shadow: 0 16px 30px rgba(187, 62, 113, 0.24);
    }

    @media (max-width: 991px) {
        .contact-page__grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .contact-page {
            width: calc(100% - 16px);
            margin: 16px auto 34px;
        }

        .contact-page__hero,
        .contact-page__panel,
        .contact-page__form-shell {
            padding: 20px 16px;
            border-radius: 24px;
        }

        .contact-page__form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="contact-page">
    <div class="contact-page__hero">
        <span class="contact-page__eyebrow"><i class="fas fa-envelope-open-text"></i> Contact Us</span>
        <h1>Reach our team with your question, request, or feedback.</h1>
        <p>Use this page to send a direct message to the website admin team. Your message will be stored in the admin inbox so it can be reviewed and answered quickly.</p>
    </div>

    <div class="contact-page__grid">
        <aside class="contact-page__panel">
            <h3>How we can help</h3>
            <p class="contact-page__meta-copy">Ask about admission support, portal access, profile issues, document submission, or any general inquiry related to the student platform.</p>

            <div class="contact-page__meta">
                <div class="contact-page__meta-card">
                    <small>Support Type</small>
                    <strong>General inquiries and portal help</strong>
                </div>
                <div class="contact-page__meta-card">
                    <small>Admin Inbox</small>
                    <strong>Messages appear directly in the admin panel</strong>
                </div>
                <div class="contact-page__meta-card">
                    <small>Useful Links</small>
                    <a href="{{ route('register') }}">Create Account</a>
                </div>
            </div>
        </aside>

        <div class="contact-page__form-shell">
            <h3>Send a Message</h3>

            <form class="contact-page__form" method="POST" action="{{ route('contact-messages.store') }}">
                @csrf

                @if (session('contact_success'))
                    <div class="contact-page__alert contact-page__alert--success">
                        {{ session('contact_success') }}
                    </div>
                @endif

                @if ($errors->has('name') || $errors->has('email') || $errors->has('subject') || $errors->has('message'))
                    <div class="contact-page__alert contact-page__alert--error">
                        Please check the required fields and submit the form again.
                    </div>
                @endif

                <div class="contact-page__form-grid">
                    <div class="contact-page__field">
                        <label for="contact_name">Name</label>
                        <input id="contact_name" type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required>
                        @error('name')
                            <span class="contact-page__error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="contact-page__field">
                        <label for="contact_email">Email</label>
                        <input id="contact_email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                        @error('email')
                            <span class="contact-page__error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="contact-page__field contact-page__field--full">
                        <label for="contact_subject">Subject</label>
                        <input id="contact_subject" type="text" name="subject" value="{{ old('subject') }}" placeholder="Write your subject" required>
                        @error('subject')
                            <span class="contact-page__error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="contact-page__field contact-page__field--full">
                        <label for="contact_message">Message</label>
                        <textarea id="contact_message" name="message" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="contact-page__error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="contact-page__actions">
                    <span class="contact-page__note">Required fields: Name, Subject, Email, and Message.</span>
                    <button class="contact-page__submit" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
