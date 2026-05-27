@extends('layouts.app')

@section('content')
<div style="text-align: center; margin-top: 20px;">
    <h1>Videos for {{ $category->name }}: {{ $subCategory->name }}</h1>
    <p>Voronezh State University of Forestry and Technology</p>
</div>

<div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-top: 20px;">
    @forelse ($videos as $video)
        <div style="
            width: 250px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        ">
            @if ($video->file_type == 'google_drive')
                <!-- Display Google Drive Video in iframe -->
                <div style="width: 100%; height: 200px; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center;">
                    <iframe src="https://drive.google.com/file/d/{{ $video->file_path }}/preview" width="100%" height="100%" style="border-radius: 8px;"></iframe>
                </div>
            @else
                <!-- Regular video display for other file types -->
                <video controls style="width: 100%; height: 200px; border-bottom: 1px solid #ddd;">
                    <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @endif

            <div style="padding: 15px;">
                <!--<p style="font-size: 16px; font-weight: bold; margin: 0;">{{ $video->title ?? 'Untitled Video' }}</p>-->
                <!--<p style="font-size: 14px; color: #555; margin: 10px 0;">{{ $video->description ?? '' }}</p>-->
                
                @if ($video->file_type == 'google_drive')
                    <!-- Google Drive Download Link -->
                    <a href="https://drive.google.com/uc?export=download&id={{ $video->file_path }}" 
                       style="
                            background: linear-gradient(135deg, #007BFF, #0056b3); 
                            color: white; 
                            border: none; 
                            padding: 12px 24px; 
                            font-size: 16px; 
                            font-weight: bold; 
                            border-radius: 8px; 
                            cursor: pointer; 
                            text-decoration: none;
                            display: inline-block;
                            transition: all 0.3s ease;"
                       onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 10px rgba(0, 0, 0, 0.2)';" 
                       onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                        Download Video
                    </a>
                @else
                    <!-- Regular Download Link for other file types -->
                    <a href="{{ asset('storage/' . $video->file_path) }}" download 
                       style="
                            background: linear-gradient(135deg, #007BFF, #0056b3); 
                            color: white; 
                            border: none; 
                            padding: 12px 24px; 
                            font-size: 16px; 
                            font-weight: bold; 
                            border-radius: 8px; 
                            cursor: pointer; 
                            text-decoration: none;
                            display: inline-block;
                            transition: all 0.3s ease;"
                       onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 10px rgba(0, 0, 0, 0.2)';" 
                       onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                        Download Video
                    </a>
                @endif
            </div>
        </div>
    @empty
        <p>No videos available for this subcategory.</p>
    @endforelse
</div>
@endsection
