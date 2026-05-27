@extends('layouts.app')

@section('content')
@php
    $settings = $publicShell['settings'] ?? [];
    $aboutTitle = $settings['about_university_title'] ?? 'About University';
    $aboutContent = $settings['about_university_content'] ?? null;
    $aboutHeaderUrl = $settings['about_university_header_url'] ?? asset('1726497377.jpg');
@endphp

<style>
    .about-university-page {
        width: min(1160px, calc(100% - 32px));
        margin: 24px auto 52px;
        display: grid;
        gap: 24px;
    }

    .about-university-hero {
        position: relative;
        overflow: hidden;
        min-height: 380px;
        border-radius: 34px;
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
        max-width: 760px;
        padding: 44px 38px;
        color: #fff;
    }

    .about-university-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.6rem 0.95rem;
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
        font-size: clamp(2.2rem, 4vw, 4rem);
        line-height: 1.03;
        font-weight: 800;
        color: #fff;
    }

    .about-university-hero__content p {
        margin: 0;
        max-width: 58ch;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.8;
    }

    .about-university-card {
        padding: 30px;
        border-radius: 30px;
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
    }

    .about-university-card__content {
        color: #4a3f4c;
        font-size: 1rem;
        line-height: 1.9;
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
            width: calc(100% - 16px);
            margin: 16px auto 34px;
        }

        .about-university-hero {
            min-height: 300px;
            border-radius: 24px;
        }

        .about-university-hero__content,
        .about-university-card {
            padding: 22px 16px;
        }
    }
</style>

<section class="about-university-page">
    <div class="about-university-hero">
        <div class="about-university-hero__media"></div>
        <div class="about-university-hero__overlay"></div>
        <div class="about-university-hero__content">
            <span class="about-university-hero__eyebrow"><i class="fas fa-university"></i> University Info</span>
            <h1>{{ $aboutTitle }}</h1>
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
</section>
@endsection
