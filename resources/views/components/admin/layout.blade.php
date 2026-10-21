@props([
    'title' => 'Dashboard',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — Prewedding & Wedding Invitation Admin</title>

    <!-- Asset Bundles (Tailwind v4, Alpine.js, SweetAlert2) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#FBF9F5] text-stone-800 font-sans antialiased selection:bg-[#C25E2E]/20 selection:text-[#C25E2E]">
    <!-- Flash Messages (SweetAlert2 - Zero Inline Script) -->
    <x-flash-message />

    <div class="min-h-screen p-3 sm:p-5 lg:p-6 flex gap-6">
        <!-- Floating Sidebar -->
        <x-admin.sidebar />

        <!-- Main Workspace Area -->
        <div class="flex-1 flex flex-col gap-6 min-w-0">
            <!-- Floating Topbar -->
            <x-admin.topbar />

            <!-- Page Dynamic Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
