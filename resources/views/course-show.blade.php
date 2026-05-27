@extends('layouts.app')

@section('content')
<style>
    .course-detail-page {
        width: min(1020px, calc(100% - 32px));
        margin: 24px auto 52px;
        display: grid;
        gap: 24px;
    }

    .course-detail-hero,
    .course-detail-body {
        padding: 30px;
        border-radius: 30px;
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
    }

    .course-detail-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 0.9rem;
        border-radius: 999px;
        background: rgba(215, 89, 139, 0.1);
        color: #bb3e71;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .course-detail-hero h1 {
        margin: 1rem 0 0.9rem;
        color: #241726;
        font-size: clamp(2rem, 4vw, 3.4rem);
        line-height: 1.05;
        font-weight: 800;
    }

    .course-detail-hero p {
        margin: 0;
        color: #6f6572;
        line-height: 1.8;
    }

    .course-detail-body {
        color: #4a3f4c;
        font-size: 1rem;
        line-height: 1.9;
    }

    .course-detail-body h2,
    .course-detail-body h3,
    .course-detail-body h4 {
        color: #241726;
    }

    @media (max-width: 767px) {
        .course-detail-page {
            width: calc(100% - 16px);
            margin: 16px auto 34px;
        }

        .course-detail-hero,
        .course-detail-body {
            padding: 22px 16px;
            border-radius: 24px;
        }
    }
</style>

<section class="course-detail-page">
    <div class="course-detail-hero">
        <span class="course-detail-hero__eyebrow"><i class="fas fa-book-open"></i> Course Details</span>
        <h1>{{ $course->title }}</h1>
        <p>Read the full course information, description, and details published from the admin course CMS.</p>
    </div>

    @if (filled(strip_tags((string) $course->description)))
        <div class="course-detail-body">
            {!! $course->description !!}
        </div>
    @else
        <div class="course-detail-body">
            <p>Coming soon.</p>
        </div>
    @endif
</section>
@endsection
