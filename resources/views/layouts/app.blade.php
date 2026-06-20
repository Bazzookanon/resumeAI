<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AI Resume Screener')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen">

    {{-- Navigation --}}
    <nav class="bg-blue-700 shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-white">
                    🤖 Resume<span class="text-orange-400">AI</span>
                </a>
                <div class="flex items-center gap-6">
                    <a href="{{ route('jobs.index') }}" class="text-sm text-blue-100 hover:text-white transition-colors">Jobs</a>
                    @if(auth()->user()?->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-100 hover:text-white transition-colors">Admin</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="bg-orange-500 text-white text-sm px-4 py-2 rounded-lg shadow-sm hover:bg-orange-600 transition-colors">+ New Job</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-blue-200 hover:text-white transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 text-green-800 rounded-lg px-4 py-3 mb-4">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded-lg px-4 py-3 mb-4">
                ⚠️ {{ session('warning') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 text-red-800 rounded-lg px-4 py-3 mb-4">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

</body>
</html>
