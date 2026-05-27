@extends('layouts.app')

@section('content')
<style>
    .campaign-page {
        width: min(1120px, calc(100% - 32px));
        margin: 32px auto 52px;
    }

    .campaign-hero {
        padding: 28px;
        border-radius: 28px;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
        box-shadow: 0 18px 38px rgba(59, 33, 53, 0.16);
    }

    .campaign-hero h1 {
        margin: 0 0 10px;
        font-size: clamp(1.9rem, 4vw, 2.8rem);
        font-weight: 800;
    }

    .campaign-hero p {
        margin: 0;
        max-width: 720px;
        color: rgba(255, 255, 255, 0.86);
        line-height: 1.75;
    }

    .campaign-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-top: 24px;
    }

    .campaign-card {
        padding: 22px;
        border-radius: 24px;
        background: #fff;
        border: 1px solid rgba(35, 23, 38, 0.08);
        box-shadow: 0 14px 28px rgba(50, 32, 48, 0.08);
    }

    .campaign-card__meta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(215, 89, 139, 0.12);
        color: #bb3e71;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .campaign-card h3 {
        margin: 16px 0 10px;
        color: #241726;
        font-size: 1.28rem;
        font-weight: 800;
    }

    .campaign-card p {
        margin: 0;
        color: #6f6572;
        line-height: 1.7;
    }

    .campaign-card ul {
        margin: 16px 0 0;
        padding-left: 18px;
        color: #241726;
    }

    .campaign-card li + li {
        margin-top: 6px;
    }

    .campaign-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-top: 20px;
    }

    .campaign-card__status {
        color: #6f6572;
        font-size: 14px;
        font-weight: 700;
    }

    .campaign-card__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 10px 18px;
        border-radius: 999px;
        background: linear-gradient(135deg, #241726, #bb3e71);
        color: #fff;
        text-decoration: none;
        font-weight: 800;
    }

    .campaign-empty {
        margin-top: 24px;
        padding: 28px;
        border-radius: 24px;
        background: #fff;
        text-align: center;
        color: #6f6572;
        border: 1px solid rgba(35, 23, 38, 0.08);
    }

    @media (max-width: 767px) {
        .campaign-page {
            width: calc(100% - 16px);
            margin: 20px auto 36px;
        }

        .campaign-grid {
            grid-template-columns: 1fr;
        }

        .campaign-hero,
        .campaign-card,
        .campaign-empty {
            border-radius: 22px;
        }
    }
</style>

<section class="campaign-page">
    <div class="campaign-hero">
        <h1>Student Campaigns</h1>
        <p>Open any active campaign from Student Services, fill in the requested details, and keep your submissions organized in one place.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-4">{{ session('success') }}</div>
    @endif

    @if ($campaigns->isEmpty())
        <div class="campaign-empty">
            No active campaigns are available right now.
        </div>
    @else
        <div class="campaign-grid">
            @foreach ($campaigns as $campaign)
                <article class="campaign-card">
                    <span class="campaign-card__meta">
                        <i class="fas fa-bullhorn"></i>
                        Student Service
                    </span>
                    <h3>{{ $campaign->title }}</h3>
                    <p>This campaign includes {{ count($campaign->field_names ?? []) }} field{{ count($campaign->field_names ?? []) === 1 ? '' : 's' }} for you to complete.</p>
                    <ul>
                        @foreach ($campaign->field_names ?? [] as $fieldName)
                            <li>{{ $fieldName }}</li>
                        @endforeach
                    </ul>
                    <div class="campaign-card__footer">
                        <div class="campaign-card__status">
                            {{ $campaign->submissions->isNotEmpty() ? 'Already submitted' : 'Pending submission' }}
                        </div>
                        <a class="campaign-card__btn" href="{{ route('campaigns.show', $campaign) }}">
                            {{ $campaign->submissions->isNotEmpty() ? 'View Submission' : 'Fill Campaign' }}
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
