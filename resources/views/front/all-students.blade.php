@extends('layouts.app')

@section('content')
<style>
    .directory-wrap {
        width: min(1320px, calc(100% - 32px));
        margin: 36px auto 48px;
    }

    .directory-panel {
        background: linear-gradient(180deg, #fffaf7 0%, #ffffff 100%);
        border: 1px solid rgba(35, 23, 38, 0.08);
        border-radius: 24px;
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.08);
        padding: 32px;
        overflow: hidden;
    }

    .directory-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .directory-header h1 {
        margin: 12px 0 10px;
        color: #241726;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.15;
        text-transform: none;
        letter-spacing: 0;
    }

    .directory-header p {
        max-width: 760px;
        margin: 0 auto;
        color: #6f6572;
        line-height: 1.8;
    }

    .directory-kicker {
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

    .directory-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
        align-items: stretch;
    }

    .directory-card {
        background: #fff;
        border: 1px solid rgba(35, 23, 38, 0.07);
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 14px 28px rgba(50, 32, 48, 0.08);
        height: 100%;
    }

    .directory-card__media {
        aspect-ratio: 1 / 1;
        background: #f8ecef;
        overflow: hidden;
    }

    .directory-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .directory-card__body {
        padding: 18px 18px 20px;
        text-align: center;
    }

    .directory-card__body h3 {
        margin: 0;
        color: #241726;
        font-size: 1.08rem;
        font-weight: 700;
        word-break: break-word;
    }

    .directory-card__meta {
        margin-top: 10px;
        color: #6f6572;
        line-height: 1.7;
        font-size: 14px;
        word-break: break-word;
    }

    .directory-footer {
        margin-top: 28px;
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .directory-footer nav {
        width: 100%;
        overflow-x: auto;
    }

    .directory-footer .pagination {
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-bottom: 0;
    }

    .directory-footer .page-item {
        list-style: none;
    }

    .directory-footer .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        min-height: 42px;
        padding: 0.55rem 0.9rem;
        border-radius: 10px;
        line-height: 1.1;
        font-size: 0.95rem;
    }

    @media (max-width: 1199px) {
        .directory-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .directory-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .directory-panel {
            padding: 26px 22px;
        }
    }

    @media (max-width: 767px) {
        .directory-wrap {
            width: calc(100% - 16px);
        }

        .directory-panel {
            padding: 22px 18px;
        }

        .directory-grid {
            grid-template-columns: 1fr;
        }

        .directory-header p {
            font-size: 14px;
        }

        .directory-card__body {
            padding: 16px;
        }

        .directory-footer .pagination {
            gap: 6px;
        }

        .directory-footer .page-item .page-link {
            padding: 0.45rem 0.7rem;
            font-size: 0.92rem;
        }
    }
</style>

<section class="directory-wrap">
    <div class="directory-panel">
        <div class="directory-header">
            <span class="directory-kicker">Student Community</span>
            <h1>All Students</h1>
            <p>Showing all approved student profiles in a mixed old and new order, 50 items per page.</p>
        </div>

        <div class="directory-grid">
            @foreach ($users as $user)
                <article class="directory-card">
                    <div class="directory-card__media">
                        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('default-avatar.png') }}" alt="{{ $user->full_name }}" loading="lazy">
                    </div>
                    <div class="directory-card__body">
                        <h3>{{ $user->full_name }}</h3>
                        <div class="directory-card__meta">
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
            @endforeach
        </div>

        <div class="directory-footer">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</section>
@endsection
