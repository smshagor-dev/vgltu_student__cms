@extends('layouts.app')

@section('content')
@php
    $settings = $publicShell['settings'] ?? [];
    $aboutTitle = $settings['about_university_title'] ?? 'About University';
    $aboutContent = $settings['about_university_content'] ?? null;
    $aboutHeaderUrl = $settings['about_university_header_url'] ?? asset('1726497377.jpg');
@endphp

<style>
    .about-university-page,
    .about-university-page *,
    .about-university-page *::before,
    .about-university-page *::after {
        box-sizing: border-box;
    }

    .about-university-page {
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

    .about-university-section {
        display: grid;
        gap: 24px;
        overflow-x: clip;
    }

    .about-university-hero {
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

    .about-university-hero__media,
    .about-university-hero__overlay {
        position: absolute;
        inset: 0;
    }

    .about-university-hero__media {
        background-image: url('{{ $aboutHeaderUrl }}');
        background-size: cover;
        background-position: center;
        transform: scale(1.02);
    }

    .about-university-hero__overlay {
        background: linear-gradient(135deg, rgba(24, 17, 29, 0.84), rgba(187, 62, 113, 0.72));
    }

    .about-university-hero__content {
        position: relative;
        z-index: 1;
        width: min(100%, 760px);
        max-width: 100%;
        padding: clamp(20px, 3vw, 32px) clamp(18px, 3vw, 28px);
        color: #fff;
        text-align: center;
    }

    .about-university-hero__eyebrow {
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

    .about-university-hero__content h1 {
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

    .about-university-hero__content p {
        margin: 0;
        max-width: 52ch;
        margin-inline: auto;
        color: rgba(255, 255, 255, 0.84);
        font-size: clamp(0.98rem, 1.4vw, 1.08rem);
        line-height: 1.7;
    }

    .about-university-card {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
        padding: 30px;
        border-radius: 30px;
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
    }

    .about-university-card__content {
        width: 100%;
        max-width: 100%;
        color: #4a3f4c;
        font-size: 1rem;
        line-height: 1.9;
        overflow-wrap: anywhere;
    }

    .about-university-card__content img,
    .about-university-card__content iframe,
    .about-university-card__content video,
    .about-university-card__content table {
        max-width: 100%;
    }

    .about-university-card__content img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
        border-radius: 20px;
    }

    .about-university-card__content iframe,
    .about-university-card__content video {
        width: 100%;
        display: block;
        border: 0;
    }

    .about-university-card__content table {
        display: block;
        overflow-x: auto;
    }

    .about-university-card__content h2,
    .about-university-card__content h3,
    .about-university-card__content h4 {
        color: #241726;
        margin-top: 0;
    }

    .about-university-empty {
        text-align: center;
        padding: 46px 26px;
        border-radius: 28px;
        background: linear-gradient(180deg, #fff7f1 0%, #ffffff 100%);
        border: 1px dashed rgba(187, 62, 113, 0.28);
        color: #6f6572;
    }

    .about-university-empty strong {
        display: block;
        margin-bottom: 0.65rem;
        color: #241726;
        font-size: 1.2rem;
    }

    @media (max-width: 767px) {
        .about-university-page {
            margin: 0 0 34px;
            padding: 16px 0 0;
        }

        .container-standard,
        .about-university-section {
            gap: 16px;
        }

        .about-university-hero {
            min-height: 240px;
        }

        .about-university-hero__content,
        .about-university-card {
            padding: 22px 16px;
        }

        .about-university-hero__content h1 {
            max-width: 100%;
            font-size: clamp(24px, 7vw, 32px);
            line-height: 1.1;
        }

        .about-university-hero__content p {
            font-size: 0.95rem;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .about-university-hero {
            min-height: 260px;
        }

        .about-university-hero__content {
            padding: 24px 22px;
        }

        .about-university-hero__content h1 {
            max-width: 720px;
            font-size: clamp(28px, 3.6vw, 40px);
        }
    }
</style>

<section class="about-university-page">
    <div class="container container-standard">
        <div class="about-university-section">
            <div class="about-university-hero">
                <div class="about-university-hero__media"></div>
                <div class="about-university-hero__overlay"></div>
                <div class="about-university-hero__content">
                    <span class="about-university-hero__eyebrow"><i class="fas fa-university"></i>About Our University</span>
                    <h2>{{ $aboutTitle }}</h2>
                    <p>Explore official details, highlights, and academic information about the university from this dedicated page.</p>
                </div>
            </div>

            @if (filled(strip_tags((string) $aboutContent)))
                <div class="about-university-card">
                    <div class="about-university-card__content">
                        {!! $aboutContent !!}
                    </div>
                </div>
            @else
                <div class="about-university-empty">
                    <strong>Coming Soon</strong>
                    <span>About University content will appear here once it is added from the Homepage CMS.</span>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
