@extends('layouts.app')

@section('content')
    <div class="iframe-container">
        <iframe 
            src="https://vgltu.ru/lc/profile" 
            title="Class Routine"
            allowfullscreen
            class="responsive-iframe"
            id="lc-iframe">
        </iframe>
        
        <div class="iframe-fallback" id="iframe-fallback">
            <p>The portal couldn't be loaded in this view.</p>
            <a href="https://vgltu.ru/lc/profile" target="_blank" class="btn btn-primary">
                Open VGLTU Portal in New Tab
            </a>
        </div>
    </div>

    <style>
        .iframe-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }

        .responsive-iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .iframe-fallback {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .iframe-container {
                height: 100vh;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iframe = document.getElementById('lc-iframe');
            const fallback = document.getElementById('iframe-fallback');
            
            iframe.onload = function() {
                // Check if iframe loaded successfully
                try {
                    // This will throw an error if cross-origin
                    if(iframe.contentWindow.location.href === 'about:blank') {
                        showFallback();
                    }
                } catch (e) {
                    showFallback();
                }
            };
            
            iframe.onerror = function() {
                showFallback();
            };
            
            function showFallback() {
                iframe.style.display = 'none';
                fallback.style.display = 'block';
            }
            
            // Initial check after short delay
            setTimeout(() => {
                try {
                    if(!iframe.contentWindow || iframe.contentWindow.location.href === 'about:blank') {
                        showFallback();
                    }
                } catch (e) {
                    showFallback();
                }
            }, 1000);
        });
    </script>
@endsection