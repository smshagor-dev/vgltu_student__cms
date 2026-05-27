@extends('layouts.app')

@section('content')
<div style="text-align: center; margin-top: 20px;">
    <h1>Photos for {{ $category->name }}: {{ $subCategory->name }}</h1>
    <p>Voronezh State University of Forestry and Technology</p>
</div>

<!-- Photo Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; justify-content: center; margin-top: 20px;">
    @forelse ($photos as $photo)
        <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; text-align: center; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); cursor: pointer;"
            onclick="showModal({{ $loop->index }})">

            @if ($photo->file_type == 'google_drive')
                <div style="width: 100%; height: 140px; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center;">
                    <iframe src="https://drive.google.com/file/d/{{ $photo->file_path }}/preview" width="100%" height="100%" style="border-radius: 8px;" loading="lazy"></iframe>
                </div>
            @else
                <img src="{{ Storage::url($photo->file_path) }}" alt="Photo" loading="lazy"
                     style="width: 100%; height: 140px; object-fit: cover;">
            @endif
        </div>
    @empty
        <p>No photos available for this subcategory.</p>
    @endforelse
</div>

<!-- Modal -->
<div id="photoModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); justify-content: center; align-items: center; flex-direction: column;">
    <div id="modalContainer" style="position: relative; text-align: center;">
        <span onclick="closeModal()" style="position: absolute; top: 10px; right: 10px; color: white; font-size: 24px; cursor: pointer;">&times;</span>
        <div id="modalContent">
            <img id="modalImg" src="" style="max-width: 90%; max-height: 80vh; border-radius: 8px;">
        </div>
        <br>
        <a id="downloadBtn" href="#" download style="
            background: linear-gradient(135deg, #28a745, #218838); 
            color: white; 
            padding: 10px 20px; 
            font-size: 14px; 
            font-weight: bold; 
            border-radius: 6px; 
            text-decoration: none; 
            display: inline-block; 
            transition: all 0.3s ease; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
            onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.15)';" 
            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
            Download Photo
        </a>
    </div>

    <!-- Left & Right Arrows -->
    <button onclick="changePhoto(-1)" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 15px; font-size: 24px; cursor: pointer; border-radius: 50%;">
        &#10094;
    </button>
    <button onclick="changePhoto(1)" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 15px; font-size: 24px; cursor: pointer; border-radius: 50%;">
        &#10095;
    </button>
</div>

<script>
    let currentIndex = 0;
    let photos = @json($photos);

    function showModal(index) {
        currentIndex = index;
        updateModal();
        document.getElementById('photoModal').style.display = 'flex';
    }

    function updateModal() {
        let modalContent = document.getElementById('modalContent');
        let downloadBtn = document.getElementById('downloadBtn');
        let modalContainer = document.getElementById('modalContainer');

        let photo = photos[currentIndex];
        let src = photo.file_type === 'google_drive'
            ? `https://drive.google.com/file/d/${photo.file_path}/preview`
            : `{{ Storage::url('') }}${photo.file_path}`;

        let downloadSrc = photo.file_type === 'google_drive'
            ? `https://drive.google.com/uc?export=download&id=${photo.file_path}`
            : src;

        // Adjust modal size dynamically based on screen width
        let screenWidth = window.innerWidth;
        let maxSize = screenWidth < 600 ? screenWidth * 0.9 : Math.min(screenWidth * 0.8, 600);
        modalContainer.style.maxWidth = `${maxSize}px`;
        modalContainer.style.maxHeight = `${maxSize * 0.7}px`;

        if (photo.file_type === 'google_drive') {
            modalContent.innerHTML = `<iframe src="${src}" width="${maxSize}" height="${maxSize * 0.7}" style="border-radius: 8px;"></iframe>`;
        } else {
            modalContent.innerHTML = `<img id="modalImg" src="${src}" style="max-width: ${maxSize}px; max-height: ${maxSize * 0.7}px; border-radius: 8px;">`;
        }

        downloadBtn.href = downloadSrc;
    }

    function changePhoto(direction) {
        currentIndex += direction;
        if (currentIndex < 0) {
            currentIndex = photos.length - 1; // Loop to last image
        } else if (currentIndex >= photos.length) {
            currentIndex = 0; // Loop to first image
        }
        updateModal();
    }

    function closeModal() {
        document.getElementById('photoModal').style.display = 'none';
    }

    // Keyboard Navigation (Arrow Keys)
    document.addEventListener('keydown', function(event) {
        if (document.getElementById('photoModal').style.display === 'flex') {
            if (event.key === 'ArrowLeft') {
                changePhoto(-1);
            } else if (event.key === 'ArrowRight') {
                changePhoto(1);
            } else if (event.key === 'Escape') {
                closeModal();
            }
        }
    });
</script>

@endsection
