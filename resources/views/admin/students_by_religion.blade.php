@extends('layouts.admin_app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Students by Religion </h2>

    <!-- Search Form -->
    <form method="GET" action="{{ route('students.by.religion') }}" class="mb-4 row justify-content-center" id="studentsByReligionFilterForm">
        <!-- Religion Dropdown -->
        <div class="col-md-4 mb-3">
            <select name="religion" class="form-control">
                <option value="" selected disabled>Select Religion</option>
                @foreach($religions as $religion)
                <option value="{{ $religion }}" {{ request('religion') == $religion ? 'selected' : '' }}>
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
                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
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

        <div class="col-md-2 mb-3">
            <a href="{{ route('students.by.religion.pdf', ['religion' => request('religion'), 'country' => request('country')]) }}" class="btn btn-danger w-100" id="studentsByReligionPdfLink">
                <i class="fa fa-download"></i> PDF
            </a>
        </div>
    </form>

    <!-- Block Cards -->
    <div id="studentsByReligionCards">
        @include('admin.partials.students_by_religion_cards', ['structuredData' => $structuredData, 'selectedReligion' => $selectedReligion, 'selectedCountry' => $selectedCountry])
    </div>
</div>

<script>
    (function () {
        const form = document.getElementById('studentsByReligionFilterForm');
        const cards = document.getElementById('studentsByReligionCards');
        const pdfLink = document.getElementById('studentsByReligionPdfLink');

        if (!form || !cards || !pdfLink) {
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
                    if (data.pdf_url) {
                        pdfLink.href = data.pdf_url;
                    }
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
</script>
@endsection
