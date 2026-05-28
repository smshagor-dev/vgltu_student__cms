@extends('layouts.admin_app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Students in Block: {{ $block }}</h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('students.by.block', ['block' => $block]) }}" class="mb-4 row justify-content-center" id="studentsByBlockFilterForm">
        <!-- Religion Dropdown -->
        <div class="col-md-4 mb-3">
            <select name="religion" class="form-control">
                <option value="" selected disabled>Select Religion</option>
                @foreach($religions as $religion)
                    <option value="{{ $religion }}" {{ $religion == request('religion') ? 'selected' : '' }}>
                        {{ $religion }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Country Dropdown -->
        <div class="col-md-4 mb-3">
            <select name="country" class="form-control">
                <option value="" selected disabled>Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ $country == request('country') ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 mb-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </form>

    <!-- Block Cards -->
    <div id="studentsByBlockCards">
        @include('admin.partials.student_by_block_cards', ['floors' => $floors])
    </div>
</div>

<script>
    (function () {
        const form = document.getElementById('studentsByBlockFilterForm');
        const cards = document.getElementById('studentsByBlockCards');

        if (!form || !cards) {
            return;
        }

        const submitFilter = function () {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const url = form.action + '?' + params.toString();

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    cards.innerHTML = data.html;
                    window.history.replaceState({}, '', url);
                })
                .catch(() => {
                    window.location.href = url;
                });
        };

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitFilter();
        });

        form.querySelectorAll('select[name="religion"], select[name="country"]').forEach(function (select) {
            select.addEventListener('change', submitFilter);
        });
    })();

    function copyRoomData(elementId) {
        const el = document.getElementById(elementId);
        if (!el) return;

        const range = document.createRange();
        range.selectNode(el);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                alert('Room data copied to clipboard!');
            } else {
                alert('Copy failed. Please try manually.');
            }
        } catch (err) {
            alert('Oops, copy not supported.');
        }

        window.getSelection().removeAllRanges();
    }
</script>

@endsection
