@extends('layouts.app')

@section('content')

<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .welcome-slider-wrap {
            width: min(1320px, calc(100% - 32px));
            margin: 0 auto;
        }

        .welcome-hero-carousel {
            height: 70vh;
            min-height: 360px;
            padding: 0;
        }

        .welcome-hero-inner {
            height: 100%;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .welcome-hero-item {
            height: 100%;
        }

        .welcome-hero-image {
            height: 100%;
            object-fit: cover;
            border-radius: 18px;
        }

        .welcome-hero-caption {
            background: rgba(0, 0, 0, 0.42);
            padding: 24px;
            border-radius: 14px;
            left: 50%;
            transform: translateX(-50%);
            bottom: 8%;
            width: min(900px, calc(100% - 40px));
        }

        header {
            text-align: center;
            padding: 50px 20px;
            color: black; /* White text */
        }

        .country-flag-row {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 18px;
        }

        .hero-text-block {
            text-align: center;
            padding: 28px 16px 8px;
        }

        .hero-text-block .hero-title {
            color: #222;
            margin-bottom: 10px;
        }

        .hero-text-block .hero-subtitle {
            color: #555;
            max-width: 760px;
        }

        .hero-gallery-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding: 12px 16px 20px;
            scroll-snap-type: x proximity;
            -webkit-overflow-scrolling: touch;
        }

        .hero-gallery-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .hero-gallery-scroll::-webkit-scrollbar-thumb {
            background: rgba(36, 23, 38, 0.22);
            border-radius: 999px;
        }

        .hero-gallery-card {
            min-width: 260px;
            max-width: 260px;
            flex: 0 0 260px;
            border-radius: 24px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 18px 34px rgba(59, 33, 53, 0.12);
            scroll-snap-align: start;
        }

        .hero-gallery-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .country-flag-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            width: 100px;
        }

        .country-flag-thumb {
            width: 100px;
            height: 64px;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            border: 3px solid #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .country-flag-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .country-flag-thumb span {
            font-size: 12px;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
        }

        .country-flag-label {
            font-size: 12px;
            font-weight: 600;
            color: #333;
            text-align: center;
            line-height: 1.4;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .hero-title {
            color: #fff;
            font-size: clamp(2rem, 4vw, 3.6rem);
            line-height: 1.1;
            margin: 0 0 12px;
            text-transform: none;
            letter-spacing: 0;
            font-weight: 800;
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.92);
            font-size: 16px;
            line-height: 1.7;
            margin: 0 auto;
            max-width: 760px;
        }

        .hero-cta {
            display: inline-block;
            margin-top: 18px;
            padding: 12px 24px;
            border-radius: 999px;
            background: linear-gradient(135deg, #f173aa, #bb3e71);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 10px 24px rgba(187, 62, 113, 0.35);
        }

        .hero-cta:hover {
            color: #fff;
        }

        .premium-section {
            width: min(1320px, calc(100% - 32px));
            margin: 34px auto 0;
        }

        .premium-panel {
            background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
            border: 1px solid rgba(35, 23, 38, 0.08);
            border-radius: 24px;
            box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
            padding: 32px;
            overflow: hidden;
        }

        .premium-heading {
            text-align: center;
            margin-bottom: 24px;
        }

        .premium-heading__actions {
            margin-top: 18px;
        }

        .premium-heading__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 22px;
            border-radius: 999px;
            background: #241726;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .premium-heading__link:hover {
            background: #bb3e71;
            color: #fff;
            transform: translateY(-2px);
        }

        .premium-heading h2 {
            margin: 10px 0 8px;
            color: #241726;
            font-size: clamp(1.9rem, 3vw, 2.8rem);
            font-weight: 800;
        }

        .premium-heading p {
            margin: 0 auto;
            max-width: 720px;
            color: #6f6572;
            line-height: 1.8;
            word-break: break-word;
        }

        .premium-kicker {
            display: inline-flex;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(215, 89, 139, 0.1);
            color: #bb3e71;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .premium-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 22px;
        }

        .premium-marquee {
            overflow: hidden;
            position: relative;
        }

        .premium-marquee::before,
        .premium-marquee::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            width: 54px;
            z-index: 1;
            pointer-events: none;
        }

        .premium-marquee::before {
            left: 0;
            background: linear-gradient(90deg, #fff 0%, rgba(255, 255, 255, 0) 100%);
        }

        .premium-marquee::after {
            right: 0;
            background: linear-gradient(270deg, #fff 0%, rgba(255, 255, 255, 0) 100%);
        }

        .premium-marquee__track {
            display: flex;
            gap: 22px;
            width: max-content;
            animation: premium-marquee-scroll var(--marquee-duration, 60s) linear infinite;
        }

        .premium-marquee:hover .premium-marquee__track {
            animation-play-state: paused;
        }

        .premium-marquee__item {
            flex: 0 0 calc((min(1320px, calc(100vw - 32px)) - 66px) / 4);
            max-width: 300px;
            min-width: 240px;
            padding: 4px 0;
        }

        @keyframes premium-marquee-scroll {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(calc(-50% - 11px));
            }
        }

        .premium-card {
            background: #fff;
            border: 1px solid rgba(35, 23, 38, 0.07);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 14px 28px rgba(50, 32, 48, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            height: 100%;
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 34px rgba(50, 32, 48, 0.12);
        }

        .premium-card__media {
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background: #f8ecef;
        }

        .premium-card__media img,
        .premium-gallery-card__media img,
        .premium-voice-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .premium-card__body {
            padding: 18px 18px 20px;
            text-align: center;
        }

        .premium-card__body h3,
        .premium-gallery-card__body h3,
        .premium-voice-card__body h3 {
            margin: 0;
            color: #241726;
            font-size: 1.12rem;
            font-weight: 700;
            word-break: break-word;
        }

        .premium-card__meta {
            margin-top: 10px;
            color: #6f6572;
            line-height: 1.7;
            font-size: 14px;
            word-break: break-word;
        }

        .premium-gallery-layout {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
        }

        .premium-gallery-panel {
            background: linear-gradient(180deg, #ffffff 0%, #fff8fb 100%);
            border: 1px solid rgba(35, 23, 38, 0.1);
            border-radius: 22px;
            padding: 24px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8), 0 12px 28px rgba(50, 32, 48, 0.06);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .premium-gallery-stack {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .premium-gallery-actions {
            margin-top: auto;
            padding-top: 18px;
            border-top: 1px solid rgba(35, 23, 38, 0.08);
            text-align: center;
        }

        .premium-gallery-card {
            display: block;
            text-decoration: none;
            background: #fff;
            border: 1px solid rgba(35, 23, 38, 0.07);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 26px rgba(50, 32, 48, 0.07);
            color: #241726;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .premium-gallery-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 34px rgba(50, 32, 48, 0.1);
            color: #241726;
        }

        .premium-gallery-card__media {
            aspect-ratio: 1.2 / 1;
            overflow: hidden;
            background: #f5edf0;
        }

        .premium-gallery-card__body {
            padding: 16px 18px 20px;
        }

        .premium-gallery-card__body span {
            display: inline-flex;
            margin-top: 10px;
            color: #bb3e71;
            font-weight: 700;
            font-size: 14px;
        }

        .premium-voices {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .premium-voice-card {
            background: #fff;
            border: 1px solid rgba(35, 23, 38, 0.07);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 14px 28px rgba(50, 32, 48, 0.08);
        }

        .premium-voice-card__media {
            aspect-ratio: 1 / 0.9;
            overflow: hidden;
            background: #f6eef1;
        }

        .premium-voice-card__body {
            padding: 20px;
            text-align: center;
        }

        .premium-voice-card__role {
            display: block;
            margin-top: 8px;
            color: #bb3e71;
            font-weight: 700;
        }

        .premium-voice-card__text {
            margin-top: 14px;
            color: #6f6572;
            line-height: 1.8;
            font-size: 14px;
            word-break: break-word;
        }

        h1 {
            font-size: 3em;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        h2 {
            font-size: 1.5em;
            font-weight: 300;
            margin-top: 10px;
            color: #0099ff; /* Lighter shade for subheading */
        }

        @media (max-width: 768px) {
            .welcome-slider-wrap {
                width: calc(100% - 16px);
            }

            .premium-section {
                width: calc(100% - 16px);
            }
        }

        @media (max-width: 1199px) {
            .premium-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 991px) {
            .welcome-hero-carousel {
                height: 58vh;
                min-height: 320px;
            }

            .welcome-hero-caption {
                padding: 18px;
                width: calc(100% - 28px);
            }

            .country-flag-row {
                gap: 12px;
            }

            .country-flag-item {
                width: 88px;
            }

            .country-flag-thumb {
                width: 88px;
                height: 58px;
            }

            .premium-gallery-layout {
                grid-template-columns: 1fr;
            }

            .premium-gallery-panel {
                padding: 20px;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            .premium-panel {
                padding: 24px 20px;
            }

            .premium-heading {
                margin-bottom: 20px;
            }

            .premium-heading h2 {
                font-size: 1.8rem;
            }

            .premium-grid,
            .premium-gallery-stack {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .premium-marquee__item {
                flex-basis: 42vw;
                min-width: 42vw;
                max-width: 42vw;
            }

            .premium-heading__link {
                width: auto;
                min-width: 210px;
            }

            .premium-voices {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px;
            }

            .premium-voices .premium-voice-card:last-child {
                grid-column: 1 / -1;
                max-width: 420px;
                margin: 0 auto;
            }
        }

        @media (max-width: 767px) {
            .welcome-hero-carousel {
                height: auto;
                min-height: 0;
            }

            .welcome-hero-inner,
            .welcome-hero-item {
                height: 320px;
            }

            .welcome-hero-caption {
                width: calc(100% - 20px);
                padding: 14px;
                bottom: 12px;
            }

            .hero-badge {
                padding: 7px 12px;
                font-size: 11px;
                letter-spacing: 0.06em;
            }

            .hero-title {
                font-size: clamp(1.45rem, 6vw, 2rem);
            }

            .hero-subtitle,
            .hero-text-block .hero-subtitle,
            .premium-heading p {
                font-size: 14px;
                line-height: 1.65;
            }

            .hero-cta,
            .premium-heading__link {
                width: 100%;
                max-width: 100%;
                padding: 12px 16px;
            }

            .hero-text-block {
                padding: 22px 8px 4px;
            }

            .hero-gallery-scroll {
                padding: 10px 8px 16px;
                gap: 12px;
            }

            .hero-gallery-card {
                min-width: 220px;
                max-width: 220px;
                flex-basis: 220px;
            }

            .hero-gallery-card img {
                height: 150px;
            }

            .country-flag-row {
                justify-content: center;
                gap: 10px;
                overflow-x: auto;
                flex-wrap: nowrap;
                padding: 0 8px 6px;
                scrollbar-width: thin;
            }

            .country-flag-item {
                flex: 0 0 82px;
                width: 82px;
            }

            .country-flag-thumb {
                width: 82px;
                height: 54px;
            }

            .country-flag-label {
                font-size: 11px;
            }

            .premium-section {
                margin-top: 20px;
            }

            .premium-panel {
                padding: 20px 14px;
                border-radius: 18px;
            }

            .premium-heading {
                margin-bottom: 18px;
            }

            .premium-heading h2 {
                font-size: 1.55rem;
                line-height: 1.2;
            }

            .premium-heading__actions {
                margin-top: 14px;
            }

            .premium-grid,
            .premium-gallery-stack {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .premium-marquee::before,
            .premium-marquee::after {
                width: 18px;
            }

            .premium-marquee__item {
                flex-basis: calc(100vw - 52px);
                min-width: calc(100vw - 52px);
                max-width: calc(100vw - 52px);
            }

            .premium-card__body,
            .premium-voice-card__body,
            .premium-gallery-card__body {
                padding: 14px;
            }

            .premium-gallery-panel {
                padding: 16px;
                border-radius: 18px;
            }

            .premium-gallery-actions {
                padding-top: 14px;
            }

            .premium-voice-card {
                border-radius: 18px;
            }

            .premium-voices {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .carousel-control-prev,
            .carousel-control-next {
                display: none;
            }

            .carousel-indicators {
                margin-bottom: 8px;
            }
        }

        @media (max-width: 480px) {
            .welcome-hero-inner,
            .welcome-hero-item {
                height: 260px;
                border-radius: 14px;
            }

            .welcome-hero-image {
                border-radius: 14px;
            }

            .welcome-hero-caption {
                border-radius: 12px;
            }

            .premium-marquee__item {
                flex-basis: calc(100vw - 40px);
                min-width: calc(100vw - 40px);
                max-width: calc(100vw - 40px);
            }
        }
    </style>

<!-- Sliders Section -->
<div id="slider" class="carousel slide welcome-slider-wrap welcome-hero-carousel" data-ride="carousel" data-interval="3000">
    <ol class="carousel-indicators">
        @foreach($sliders as $key => $slider)
            <li data-target="#slider" data-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"></li>
        @endforeach
    </ol>

    <div class="carousel-inner welcome-hero-inner">
        @foreach($sliders as $key => $slider)
            <div class="carousel-item welcome-hero-item {{ $key === 0 ? 'active' : '' }}">
                <img src="{{ asset('images/sliders/' . $slider->image) }}" class="d-block w-100 welcome-hero-image" alt="Slider Image">
                <div class="carousel-caption welcome-hero-caption">
                    @if (!empty($heroSection?->badge_text))
                        <div class="hero-badge">{{ $heroSection->badge_text }}</div>
                    @endif
                    @if (!empty($heroSection?->cta_text) && !empty($heroSection?->cta_link))
                        <div>
                            <a href="{{ $heroSection->cta_link }}" class="hero-cta">{{ $heroSection->cta_text }}</a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev" style="width: 5%;">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5); border-radius: 50%; padding: 10px;"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#slider" role="button" data-slide="next" style="width: 5%;">
        <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5); border-radius: 50%; padding: 10px;"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

@if (($countryFlags ?? collect())->isNotEmpty() || $studyDestinations->isNotEmpty())
    <div class="welcome-slider-wrap">
        <div class="hero-text-block">
            <h3 class="hero-title">{{ $heroSection?->title ?: 'Voronezh State University of Forestry and Technologies' }}</h3>
            <p class="hero-subtitle">{{ $heroSection?->subtitle ?: 'Official digital home for students, alumni, events, and community media.' }}</p>
        </div>
        <div class="country-flag-row">
            @forelse (($countryFlags ?? collect()) as $flag)
                <div class="country-flag-item" title="{{ $flag->label }}">
                    <div class="country-flag-thumb">
                        @if ($flag->image_path)
                            <img src="{{ \App\Support\PublicAsset::url($flag->image_path) }}" alt="{{ $flag->label }}">
                        @else
                            <span>{{ \Illuminate\Support\Str::limit($flag->label, 2, '') }}</span>
                        @endif
                    </div>
                    <div class="country-flag-label">{{ $flag->label }}</div>
                </div>
            @empty
                @foreach ($studyDestinations as $destination)
                    <div class="country-flag-item" title="{{ $destination->name }}">
                        <div class="country-flag-thumb">
                            @if ($destination->flag_image_path)
                                <img src="{{ \App\Support\PublicAsset::url($destination->flag_image_path) }}" alt="{{ $destination->name }}">
                            @else
                                <span>{{ \Illuminate\Support\Str::limit($destination->name, 2, '') }}</span>
                            @endif
                        </div>
                        <div class="country-flag-label">{{ $destination->name }}</div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>
@elseif (!empty($heroSection?->title) || !empty($heroSection?->subtitle))
    <div class="welcome-slider-wrap">
        <div class="hero-text-block">
            <h3 class="hero-title">{{ $heroSection?->title ?: 'Voronezh State University of Forestry and Technologies' }}</h3>
            <p class="hero-subtitle">{{ $heroSection?->subtitle ?: 'Official digital home for students, alumni, events, and community media.' }}</p>
        </div>
    </div>
@endif

@if (($heroSection?->images ?? collect())->isNotEmpty())
    <div class="welcome-slider-wrap">
        <div class="hero-gallery-scroll" aria-label="Hero background image gallery">
            @foreach ($heroSection->images as $image)
                <div class="hero-gallery-card">
                    <img src="{{ \App\Support\PublicAsset::url($image->image_path) }}" alt="Hero gallery image {{ $loop->iteration }}" loading="lazy">
                </div>
            @endforeach
        </div>
    </div>
@endif


<section class="premium-section">
    <div class="premium-panel">
        <div class="premium-heading">
            <span class="premium-kicker">Student Community</span>
            <h2>Our Students</h2>
            <p>Meet active students from the VGLTU Asian community through a cleaner, more institutional presentation.</p>
            <div class="premium-heading__actions">
                <a href="{{ route('students.all') }}" class="premium-heading__link">View All Students</a>
            </div>
        </div>
        @php($studentLoop = $users->isNotEmpty() ? $users->concat($users) : collect())
        @php($studentDuration = max($users->count() * 6, 60))
        <div class="premium-marquee" style="--marquee-duration: {{ $studentDuration }}s;" aria-label="Our Students auto scrolling list">
            <div class="premium-marquee__track">
                @foreach($studentLoop as $user)
                    <div class="premium-marquee__item">
                        <article class="premium-card">
                            <div class="premium-card__media">
                                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('default-avatar.png') }}" alt="{{ $user->full_name }}" loading="lazy">
                            </div>
                            <div class="premium-card__body">
                                <h3>{{ $user->full_name }}</h3>
                                <div class="premium-card__meta">
                                    <div><strong>Department:</strong>
                                        @if ($user->department == 'Computer Science and Technology')
                                            Computer Science
                                        @elseif ($user->department == 'Prepetory Language Course')
                                            Language Course
                                        @else
                                            {{ $user->department ?: 'N/A' }}
                                        @endif
                                    </div>
                                    <div><strong>Course Type:</strong> {{ $user->course_type ?: 'N/A' }}</div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="premium-section">
    <div class="premium-panel">
        <div class="premium-gallery-layout">
            <div class="premium-gallery-panel">
                <div class="premium-heading text-start">
                    <span class="premium-kicker">Media Archive</span>
                    <h2>Photos Gallery</h2>
                    <p>Browse campus memories, community moments, and event highlights in a more polished gallery system.</p>
                </div>
                <div class="premium-gallery-stack">
                    @foreach ($photoCategories as $photoCategory)
                        <a href="{{ url('category/photos/' . $photoCategory->id) }}" class="premium-gallery-card">
                            <div class="premium-gallery-card__media">
                                <img src="{{ asset('storage/' . $photoCategory->photo) }}" alt="{{ $photoCategory->name }}">
                            </div>
                            <div class="premium-gallery-card__body">
                                <h3>{{ $photoCategory->name }}</h3>
                                <span>View Album</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="premium-gallery-actions">
                    <a href="{{ route('photo-galleries.all') }}" class="premium-heading__link">View All Photos</a>
                </div>
            </div>

            <div class="premium-gallery-panel">
                <div class="premium-heading text-start">
                    <span class="premium-kicker">Moving Stories</span>
                    <h2>Videos Gallery</h2>
                    <p>Watch student activities, celebrations, and university highlights through a richer video collection layout.</p>
                </div>
                <div class="premium-gallery-stack">
                    @foreach ($videoCategories as $videoCategory)
                        <a href="{{ url('category/videos/' . $videoCategory->id) }}" class="premium-gallery-card">
                            <div class="premium-gallery-card__media">
                                <img src="{{ asset('storage/' . $videoCategory->photo) }}" alt="{{ $videoCategory->name }}">
                            </div>
                            <div class="premium-gallery-card__body">
                                <h3>{{ $videoCategory->name }}</h3>
                                <span>Watch Collection</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="premium-gallery-actions">
                    <a href="{{ route('video-galleries.all') }}" class="premium-heading__link">View All Videos</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="premium-section">
    <div class="premium-panel">
        <div class="premium-heading">
            <span class="premium-kicker">Leadership Voices</span>
            <h2>University Messages</h2>
            <p>Key voices that represent the university and student community in a more formal editorial presentation.</p>
        </div>
        <div class="premium-voices">
            <article class="premium-voice-card">
                <div class="premium-voice-card__media">
                    <img src="{{ asset('rector.png') }}" alt="Rector">
                </div>
                <div class="premium-voice-card__body">
                    <h3>Drapalyuk Mikhail Valentinovich</h3>
                    <span class="premium-voice-card__role">Rector Of The University</span>
                    <div class="premium-voice-card__text">
                        Education empowers minds, opens opportunities, and helps build a more thoughtful and progressive society.
                    </div>
                </div>
            </article>

            <article class="premium-voice-card">
                <div class="premium-voice-card__media">
                    <img src="{{ asset('dean.png') }}" alt="Dean">
                </div>
                <div class="premium-voice-card__body">
                    <h3>Nikolay Zhuzhukin</h3>
                    <span class="premium-voice-card__role">Dean of the University</span>
                    <div class="premium-voice-card__text">
                        A strong education system shapes resilient individuals and creates the foundation for a thriving future.
                    </div>
                </div>
            </article>

            <article class="premium-voice-card">
                <div class="premium-voice-card__media">
                    <img src="{{ asset('shuvo_bhai.png') }}" alt="University Representative">
                </div>
                <div class="premium-voice-card__body">
                    <h3>Md Rahman Mahmudur</h3>
                    <span class="premium-voice-card__role">University Representative</span>
                    <div class="premium-voice-card__text">
                        Learning inspires ambition, removes barriers, and helps students build a brighter future with confidence.
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="premium-section">
    <div class="premium-panel">
        <div class="premium-heading">
            <span class="premium-kicker">Alumni Network</span>
            <h2>Our Passed Students</h2>
            <p>Former students and alumni presented in a refined card system that better matches the premium homepage layout.</p>
            <div class="premium-heading__actions">
                <a href="{{ route('passed-students.all') }}" class="premium-heading__link">View All Passed Students</a>
            </div>
        </div>
        @php($passedStudentLoop = $students->isNotEmpty() ? $students->concat($students) : collect())
        @php($passedStudentDuration = max($students->count() * 6, 72))
        <div class="premium-marquee" style="--marquee-duration: {{ $passedStudentDuration }}s;" aria-label="Our Passed Students auto scrolling list">
            <div class="premium-marquee__track">
                @foreach($passedStudentLoop as $student)
                    <div class="premium-marquee__item">
                        <article class="premium-card">
                            <div class="premium-card__media">
                                <img src="{{ $student->photo_path ? asset('storage/' . $student->photo_path) : asset('default-avatar.png') }}" alt="{{ $student->name }}">
                            </div>
                            <div class="premium-card__body">
                                <h3 title="{{ $student->name }}">{{ $student->name }}</h3>
                                <div class="premium-card__meta">
                                    {!! implode('<br>', array_map(function ($d, $dep, $year) {
                                        $dep = $dep === 'Computer Science and Technology' ? 'Computer Science' : $dep;
                                        return e(trim("$d in $dep $year"));
                                    }, json_decode($student->degree, true) ?? [], json_decode($student->department, true) ?? [], json_decode($student->pass_year, true) ?? [])) ?: 'Academic details unavailable' !!}
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
