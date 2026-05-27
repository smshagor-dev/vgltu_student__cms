@extends('layouts.app')

@section('content')
<style>
    .gallery-directory-wrap {
        width: min(1320px, calc(100% - 32px));
        margin: 36px auto 48px;
    }

    .gallery-directory-panel {
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        border-radius: 24px;
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
        padding: 32px;
    }

    .gallery-directory-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .gallery-directory-kicker {
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

    .gallery-directory-header h1 {
        margin: 12px 0 10px;
        color: #241726;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.15;
    }

    .gallery-directory-header p {
        max-width: 760px;
        margin: 0 auto;
        color: #6f6572;
        line-height: 1.8;
    }

    .gallery-directory-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
    }

    .gallery-directory-card {
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

    .gallery-directory-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px rgba(50, 32, 48, 0.1);
        color: #241726;
    }

    .gallery-directory-card__media {
        aspect-ratio: 1.2 / 1;
        overflow: hidden;
        background: #f5edf0;
    }

    .gallery-directory-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-directory-card__body {
        padding: 16px 18px 20px;
        text-align: center;
    }

    .gallery-directory-card__body h3 {
        margin: 0;
        color: #241726;
        font-size: 1.08rem;
        font-weight: 700;
        word-break: break-word;
    }

    .gallery-directory-card__body span {
        display: inline-flex;
        margin-top: 10px;
        color: #bb3e71;
        font-weight: 700;
        font-size: 14px;
    }

    @media (max-width: 1199px) {
        .gallery-directory-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .gallery-directory-wrap {
            width: calc(100% - 16px);
        }

        .gallery-directory-panel {
            padding: 22px 18px;
        }

        .gallery-directory-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="gallery-directory-wrap">
    <div class="gallery-directory-panel">
        <div class="gallery-directory-header">
            <span class="gallery-directory-kicker">{{ $kicker }}</span>
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
        </div>

        <div class="gallery-directory-grid">
            @foreach ($categories as $category)
                <a href="{{ route($routeName, $category->id) }}" class="gallery-directory-card">
                    <div class="gallery-directory-card__media">
                        <img src="{{ asset('storage/' . $category->photo) }}" alt="{{ $category->name }}">
                    </div>
                    <div class="gallery-directory-card__body">
                        <h3>{{ $category->name }}</h3>
                        <span>{{ $buttonText }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
