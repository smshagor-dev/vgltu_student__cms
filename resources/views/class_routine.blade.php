@extends('layouts.app')

@section('content')
    <div class="iframe-container">
        <iframe 
            src="https://vgltu.ru/obuchayushchimsya/raspisanie-zanyatij/" 
            title="Class Routine"
            allowfullscreen
            class="responsive-iframe">
        </iframe>
    </div>

    <style>
        /* Ensuring the iframe takes up full screen on all devices */
        .iframe-container {
            position: relative;
            width: 100%;
            height: 100vh; /* Full viewport height */
        }

        .responsive-iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Adjustments for mobile devices */
        @media (max-width: 768px) {
            .iframe-container {
                height: 100vh; /* 100% of the viewport height */
            }
        }

        @media (max-width: 480px) {
            .iframe-container {
                height: 100vh; /* Ensure it takes full height on mobile too */
            }

            .responsive-iframe {
                border-radius: 8px; /* Optional: Rounded corners on mobile */
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Shadow for mobile look */
            }
        }
    </style>
@endsection
