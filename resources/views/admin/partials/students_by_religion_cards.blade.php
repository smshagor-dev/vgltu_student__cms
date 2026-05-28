<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    @foreach($structuredData as $block => $floors)
    <div class="col">
        <a href="{{ route('students.by.block', ['block' => $block, 'religion' => $selectedReligion, 'country' => $selectedCountry]) }}"
            class="card h-100 text-center shadow-sm text-decoration-none">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $block }}</h5>
                <p class="card-text text-secondary">Total Rooms: <strong>{{ count($floors) }}</strong></p>

                @php
                $totalStudents = 0;
                foreach ($floors as $rooms) {
                    foreach ($rooms as $room) {
                        $totalStudents += count($room);
                    }
                }
                @endphp

                <p class="card-text text-secondary">Total Students: <strong>{{ $totalStudents }}</strong></p>
            </div>

            <div class="card-footer">
                <small class="text-muted">View Details</small>
            </div>
        </a>
    </div>
    @endforeach
</div>
