@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Media Gallery</h1>

        <!-- Display Photos with View and Download options -->
        <h2>Photos</h2>
        <div class="row">
            @foreach($photos as $photo)
                <div class="col-md-3 mb-3">
                    <div class="card" style="width: 100%; height: 250px; overflow: hidden;">
                        <img src="{{ Storage::url($photo->file_path) }}" class="img-fluid" alt="Photo" style="height: 100%; object-fit: cover;">
                    </div>
                    <div class="mt-2 text-center">
                        <!-- View Photo -->
                        <a href="{{ Storage::url($photo->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View</a>
                        <!-- Download Photo -->
                        <a href="{{ route('front.media.download.photo', $photo->id) }}" class="btn btn-success btn-sm">Download</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Display Videos with View and Download options -->
        <h2>Videos</h2>
        <div class="row">
            @foreach($videos as $video)
                <div class="col-md-6 mb-3">
                    <div class="card" style="width: 100%; height: 250px; overflow: hidden;">
                        <video width="100%" height="100%" controls style="object-fit: cover;">
                            <source src="{{ Storage::url($video->file_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="mt-2 text-center">
                        <!-- View Video -->
                        <a href="{{ Storage::url($video->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View</a>
                        <!-- Download Video -->
                        <a href="{{ route('front.media.download.video', $video->id) }}" class="btn btn-success btn-sm">Download</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
