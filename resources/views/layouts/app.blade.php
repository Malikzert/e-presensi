<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen {{ request()->is('admin*') ? 'bg-emerald-50/30' : 'bg-gray-100' }}">
            
            @if(request()->is('admin*'))
                {{-- Admin tidak pakai navbar default, tapi kita tetap butuh tombol logout atau profil --}}
                {{-- Kita bisa memanggil navs admin di dalam sini atau biarkan navs admin dipanggil di tiap file --}}
            @else
                @include('layouts.navigation')
            @endif

            @if(!request()->is('admin*'))
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>