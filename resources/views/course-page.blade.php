@extends('layouts.app')

@section('content')
@php
    $settings = $publicShell['settings'] ?? [];
    $pageHeaderUrl = $settings['courses_header_url'] ?? asset('1726497377.jpg');
@endphp

<style>
    .course-detail-page,
    .course-detail-page *,
    .course-detail-page *::before,
    .course-detail-page *::after {
        box-sizing: border-box;
    }

    .course-detail-page {
        width: 100%;
        margin: 0 0 52px;
        padding: 24px 0 0;
    }

    .container-standard {
        display: grid;
        width: 100%;
        margin: 0 auto;
        gap: 24px;
        overflow-x: clip;
        padding-left: 0;
        padding-right: 0;
    }

    .course-detail-section {
        display: grid;
        gap: 24px;
        overflow-x: clip;
    }

    .course-detail-hero {
        width: 100%;
        max-width: 100%;
        position: relative;
        overflow: hidden;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: clamp(20px, 3vw, 34px);
        background: #241726;
        box-shadow: 0 28px 56px rgba(76, 42, 65, 0.18);
    }

    .course-detail-hero__media,
    .course-detail-hero__overlay {
        position: absolute;
        inset: 0;
    }

    .course-detail-hero__media {
        background-image: url('{{ $pageHeaderUrl }}');
        background-size: cover;
        background-position: center;
        transform: scale(1.02);
    }

    .course-detail-hero__overlay {
        background: linear-gradient(135deg, rgba(24, 17, 29, 0.84), rgba(187, 62, 113, 0.72));
    }

    .course-detail-hero__content {
        position: relative;
        z-index: 1;
        width: min(100%, 760px);
        max-width: 100%;
        padding: clamp(20px, 3vw, 32px) clamp(18px, 3vw, 28px);
        color: #fff;
        text-align: center;
    }

    .course-detail-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.6rem 0.95rem;
        justify-content: center;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.16);
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .course-detail-hero__content h1 {
        margin: 1rem 0 0.9rem;
        max-width: 800px;
        margin-inline: auto;
        font-size: clamp(24px, 3vw, 48px);
        line-height: 1.1;
        font-weight: 800;
        color: #fff;
        text-wrap: balance;
        text-align: center;
        word-break: normal;
        overflow-wrap: break-word;
    }

    .course-detail-hero__content p {
        margin: 0;
        max-width: 52ch;
        margin-inline: auto;
        color: rgba(255, 255, 255, 0.84);
        font-size: clamp(0.98rem, 1.4vw, 1.08rem);
        line-height: 1.7;
    }

    .course-detail-empty {
        text-align: center;
        padding: 46px 26px;
        border-radius: 28px;
        background: linear-gradient(180deg, #fff7f1 0%, #ffffff 100%);
        border: 1px dashed rgba(187, 62, 113, 0.28);
        color: #6f6572;
    }

    .course-detail-empty strong {
        display: block;
        margin-bottom: 0.65rem;
        color: #241726;
        font-size: 1.2rem;
    }

    @media (max-width: 767px) {
        .course-detail-page {
            margin: 0 0 34px;
            padding: 16px 0 0;
        }

        .container-standard,
        .course-detail-section {
            gap: 16px;
        }

        .course-detail-hero {
            min-height: 240px;
        }

        .course-detail-hero__content,
        .course-detail-empty {
            padding: 22px 16px;
        }

        .course-detail-hero__content h1 {
            max-width: 100%;
            font-size: clamp(24px, 7vw, 32px);
            line-height: 1.1;
        }

        .course-detail-hero__content p {
            font-size: 0.95rem;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .course-detail-hero {
            min-height: 260px;
        }

        .course-detail-hero__content {
            padding: 24px 22px;
        }

        .course-detail-hero__content h1 {
            max-width: 720px;
            font-size: clamp(28px, 3.6vw, 40px);
        }
    }
</style>

<section class="course-detail-page">
    <div class="container container-standard">
        <div class="course-detail-section">
            <div class="course-detail-hero">
                <div class="course-detail-hero__media"></div>
                <div class="course-detail-hero__overlay"></div>
                <div class="course-detail-hero__content">
                    <span class="course-detail-hero__eyebrow"><i class="fas fa-book-open"></i>Course Page</span>
                    <h1>Course</h1>
                    <p>Course content is being prepared and will be published here soon.</p>
                </div>
            </div>

            <div class="course-detail-empty">
                <strong>Coming Soon</strong>
                <span>Course page content will appear here soon.</span>
            </div>
        </div>
    </div>
</section>
@endsection
