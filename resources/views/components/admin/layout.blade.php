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
<body class="h-full bg-[#FBF9F5] text-stone-800 font-sans antialiased selection:bg-[#C25E2E]/20 selection:text-[#C25E2E] overflow-hidden">
    <!-- Flash Messages (SweetAlert2 - Zero Inline Script) -->
    <x-flash-message />

    <div class="h-screen p-3 sm:p-5 lg:p-6 flex gap-6 overflow-hidden">
        <!-- Floating Sidebar (Stays fixed) -->
        <x-admin.sidebar />

        <!-- Main Workspace Area -->
        <div class="flex-1 flex flex-col gap-6 min-w-0 h-full min-h-0">
            <!-- Floating Topbar (Stays fixed at top) -->
            <x-admin.topbar />

            <!-- Page Dynamic Content (Scrollable) -->
            <main class="flex-1 overflow-y-auto custom-scrollbar pr-1 pb-4 min-h-0 flex flex-col justify-between">
                <div>
                    {{ $slot }}
                </div>

                <!-- Professional Admin Footer -->
                <x-admin.footer />
            </main>
        </div>
    </div>
</body>
</html>
