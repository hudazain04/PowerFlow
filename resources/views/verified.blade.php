<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Verification Success') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md text-center max-w-md w-full" role="alert">
        @if(session('error'))
            <svg class="mx-auto h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="text-2xl font-bold text-gray-800 mt-4">{{ __('Verification Failed') }}</h1>
            <p class="text-gray-600 mt-2">{{ session('error') }}</p>
        @else
            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <h1 class="text-2xl font-bold text-gray-800 mt-4">{{ __('Verification Successful!') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Your account has been successfully verified.') }}</p>
        @endif

        @auth
            <a href="{{ route('dashboard') }}" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                {{ __('Go to Dashboard') }}
            </a>
{{--        @else--}}
{{--            <a href="{{ url('/') }}" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">--}}
{{--                {{ __('Return Home') }}--}}
{{--            </a>--}}
        @endauth
    </div>
</div>
</body>
</html>
