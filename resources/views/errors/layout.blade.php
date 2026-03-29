<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Nepstrading</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'DM Serif Display', serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-6 selection:bg-purple-100 selection:text-purple-900">
    <div class="max-w-4xl w-full grid md:grid-cols-2 gap-12 items-center">
        <!-- Illustration Side -->
        <div class="flex justify-center md:order-last">
            <div class="relative w-full max-w-sm aspect-square">
                <!-- Background Glow -->
                <div class="absolute inset-0 bg-gradient-to-tr from-purple-200 to-blue-100 rounded-full blur-3xl opacity-60 animate-pulse"></div>
                
                <!-- Main Image -->
                <div class="relative z-10 animate-float">
                    @yield('illustration')
                </div>
            </div>
        </div>

        <!-- Content Side -->
        <div class="text-center md:text-left space-y-8">
            <div>
                <span class="inline-block px-4 py-1.5 rounded-full bg-purple-100 text-purple-700 text-xs font-bold uppercase tracking-widest mb-6">
                    Error @yield('code')
                </span>
                <h1 class="font-serif text-5xl md:text-6xl lg:text-7xl leading-tight text-slate-900 mb-6">
                    @yield('heading')
                </h1>
                <p class="text-slate-500 text-lg md:text-xl leading-relaxed max-w-md mx-auto md:mx-0">
                    @yield('message')
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start pt-4">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-8 py-4 bg-slate-900 text-white rounded-2xl font-semibold hover:bg-slate-800 transition-all active:scale-95 shadow-xl shadow-slate-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Home
                </a>
                <a href="mailto:support@nepstrading.com.au" class="inline-flex items-center justify-center px-8 py-4 bg-white text-slate-900 border border-slate-200 rounded-2xl font-semibold hover:bg-slate-50 transition-all active:scale-95">
                    Contact Support
                </a>
            </div>

            <div class="pt-12 border-t border-slate-200/60">
                <div class="flex items-center justify-center md:justify-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-white font-bold text-xs ring-4 ring-white shadow-sm">N</div>
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Nepstrading Premium</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
