@extends('layouts.app')

@section('content')
@php
    $settings = $publicShell['settings'] ?? [];
    $pageTitle = $settings['courses_title'] ?? 'Courses';
    $pageHeaderUrl = $settings['courses_header_url'] ?? asset('1726497377.jpg');
@endphp

<style>
    .page-courses-layout,
    .page-courses-layout *,
    .page-courses-layout *::before,
    .page-courses-layout *::after {
        box-sizing: border-box;
    }

    .page-courses-layout {
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

    .page-courses-section {
        display: grid;
        gap: 24px;
        overflow-x: clip;
    }

    .page-courses-hero {
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

    .page-courses-hero__media,
    .page-courses-hero__overlay {
        position: absolute;
        inset: 0;
    }

    .page-courses-hero__media {
        background-image: url('{{ $pageHeaderUrl }}');
        background-size: cover;
        background-position: center;
        transform: scale(1.02);
    }

    .page-courses-hero__overlay {
        background: linear-gradient(135deg, rgba(24, 17, 29, 0.84), rgba(187, 62, 113, 0.72));
    }

    .page-courses-hero__content {
        position: relative;
        z-index: 1;
        width: min(100%, 760px);
        max-width: 100%;
        padding: clamp(20px, 3vw, 32px) clamp(18px, 3vw, 28px);
        color: #fff;
        text-align: center;
    }

    .page-courses-hero__eyebrow {
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

    .page-courses-hero__content h1 {
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

    .page-courses-hero__content p {
        margin: 0;
        max-width: 52ch;
        margin-inline: auto;
        color: rgba(255, 255, 255, 0.84);
        font-size: clamp(0.98rem, 1.4vw, 1.08rem);
        line-height: 1.7;
    }

    .course-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .course-card {
        display: block;
        height: 100%;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
        padding: 30px;
        border-radius: 30px;
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
        color: #241726;
        text-decoration: none;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 42px rgba(59, 33, 53, 0.12);
        color: #241726;
    }

    .course-card__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 0.85rem;
        border-radius: 999px;
        background: rgba(215, 89, 139, 0.1);
        color: #bb3e71;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .course-card h3 {
        color: #241726;
        margin: 1rem 0 0.8rem;
        font-size: 1.25rem;
        font-weight: 800;
    }

    .course-card p {
        margin: 0;
        color: #6f6572;
        line-height: 1.8;
    }

    .course-card__link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-top: 1rem;
        color: #bb3e71;
        font-weight: 800;
    }

    .page-courses-empty {
        text-align: center;
        padding: 46px 26px;
        border-radius: 28px;
        background: linear-gradient(180deg, #fff7f1 0%, #ffffff 100%);
        border: 1px dashed rgba(187, 62, 113, 0.28);
        color: #6f6572;
    }

    .page-courses-empty strong {
        display: block;
        margin-bottom: 0.65rem;
        color: #241726;
        font-size: 1.2rem;
    }

    @media (max-width: 767px) {
        .page-courses-layout {
            margin: 0 0 34px;
            padding: 16px 0 0;
        }

        .container-standard,
        .page-courses-section {
            gap: 16px;
        }

        .page-courses-hero {
            min-height: 240px;
        }

        .page-courses-hero__content,
        .course-card {
            padding: 22px 16px;
        }

        .page-courses-hero__content h1 {
            max-width: 100%;
            font-size: clamp(24px, 7vw, 32px);
            line-height: 1.1;
        }

        .page-courses-hero__content p {
            font-size: 0.95rem;
        }

        .course-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .page-courses-hero {
            min-height: 260px;
        }

        .page-courses-hero__content {
            padding: 24px 22px;
        }

        .page-courses-hero__content h1 {
            max-width: 720px;
            font-size: clamp(28px, 3.6vw, 40px);
        }

        .course-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

<section class="page-courses-layout">
    <div class="container container-standard">
        <div class="page-courses-section">
            <div class="page-courses-hero">
                <div class="page-courses-hero__media"></div>
                <div class="page-courses-hero__overlay"></div>
                <div class="page-courses-hero__content">
                    <span class="page-courses-hero__eyebrow"><i class="fas fa-book-open"></i>Course Page</span>
                    <h1>{{ $pageTitle }}</h1>
                    <p>Explore the latest course-related information, guidance, and updates published.</p>
                </div>
            </div>

            @if (($courses ?? collect())->isNotEmpty())
                <div class="course-grid">
                    @foreach ($courses as $course)
                        <a class="course-card" href="{{ route('courses.show', $course) }}">
                            <span class="course-card__eyebrow"><i class="fas fa-book-open"></i> Course</span>
                            <h3>{{ $course->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags((string) $course->description)), 160) ?: 'Full course details are available inside this page.' }}</p>
                            <span class="course-card__link">Open Details <i class="fas fa-arrow-right"></i></span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="page-courses-empty">
                    <strong>Coming Soon</strong>
                    <span>Course cards will appear here once they are added from the admin CMS.</span>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
