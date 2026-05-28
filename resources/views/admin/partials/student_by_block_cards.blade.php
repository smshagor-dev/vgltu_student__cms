<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    @foreach($floors as $floor => $rooms)
        <div class="col">
            <div class="card h-100 text-center shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $floor }}</h5>
                    <ul class="list-unstyled">
                        @foreach($rooms as $roomNumber => $students)
                            <li>
                                Room Number: {{ $roomNumber }} ({{ count($students) }})
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#roomModal{{ $roomNumber }}">
                                    View
                                </button>

                                <div class="modal fade" id="roomModal{{ $roomNumber }}" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="roomModalLabel">Students in Room {{ $roomNumber }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <button class="btn btn-sm btn-primary mb-2" onclick="copyRoomData('roomData{{ $roomNumber }}')">Copy</button>

                                                <ul id="roomData{{ $roomNumber }}" style="text-align: left;">
                                                    @foreach($students as $student)
                                                        <li>Room number: {{ $student->room_number }} - {{ $student->full_name }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer">
                    <small class="text-muted">View Details</small>
                </div>
            </div>
        </div>
    @endforeach
</div>
