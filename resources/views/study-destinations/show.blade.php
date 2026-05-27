@extends('layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
                    @if ($flagUrl)
                        <img src="{{ $flagUrl }}" alt="{{ $destination->name }} flag" style="width: 92px; height: 92px; object-fit: cover; border-radius: 24px;">
                    @endif
                    <div>
                        <div class="text-uppercase text-muted small mb-2">Study Destination</div>
                        <h1 class="display-6 fw-bold mb-2">{{ $destination->name }}</h1>
                        <p class="text-muted mb-0">Slug: {{ $destination->slug }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
