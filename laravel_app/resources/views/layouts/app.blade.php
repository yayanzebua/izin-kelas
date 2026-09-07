<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIPEKA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        (function () {
            const getTheme = () => localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (getTheme() === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .glass {
            @apply bg-white/80 dark:bg-slate-900/80 backdrop-blur-md;
        }

        .sidebar-link {
            transition: all 0.3s;
        }

        .sidebar-link:hover {
            background-color: #f1f5f9;
            color: #3b82f6;
        }

        .sidebar-active {
            background-color: #eff6ff;
            color: #3b82f6;
            border-right: 4px solid #3b82f6;
        }

        /* === Floating Global Loader === */
        #global-loader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.96);
            z-index: 99999;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px 12px 14px;
            border-radius: 999px;
            border: 1px solid transparent;
            background:
                linear-gradient(rgba(255, 255, 255, 0.94), rgba(255, 255, 255, 0.94)) padding-box,
                linear-gradient(90deg,
                    rgba(37, 99, 235, 0.85),
                    rgba(99, 102, 241, 0.78),
                    rgba(14, 165, 233, 0.65),
                    rgba(37, 99, 235, 0.85)) border-box;
            background-size: 100% 100%, 260% 100%;
            background-position: 0 0, 0% 50%;
            box-shadow:
                0 18px 55px -28px rgba(2, 6, 23, 0.55),
                0 12px 30px -18px rgba(37, 99, 235, 0.35);
            backdrop-filter: blur(18px) saturate(1.2);
            -webkit-backdrop-filter: blur(18px) saturate(1.2);
            isolation: isolate;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
            pointer-events: none;
            animation: loaderBorder 3.2s ease-in-out infinite;
        }

        @keyframes loaderBorder {

            0%,
            100% {
                background-position: 0 0, 0% 50%;
            }

            50% {
                background-position: 0 0, 100% 50%;
            }
        }

        #global-loader::before {
            content: '';
            position: absolute;
            inset: 1px;
            border-radius: 999px;
            background:
                radial-gradient(120px 40px at 18% 0%, rgba(59, 130, 246, 0.12), transparent 60%),
                radial-gradient(120px 40px at 82% 100%, rgba(139, 92, 246, 0.10), transparent 60%);
            pointer-events: none;
            z-index: -1;
        }

        #global-loader.active {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        .loader-spinner {
            width: 22px;
            height: 22px;
            position: relative;
            flex: 0 0 auto;
        }

        .loader-spinner::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 999px;
            background: conic-gradient(from 180deg, rgba(37, 99, 235, 1), rgba(99, 102, 241, 1), rgba(14, 165, 233, 1), rgba(37, 99, 235, 1));
            -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 calc(100% - 3px));
            mask: radial-gradient(farthest-side, transparent calc(100% - 3px), #000 calc(100% - 3px));
            animation: loaderSpin 0.75s linear infinite;
            filter: drop-shadow(0 6px 12px rgba(59, 130, 246, 0.18));
        }

        .loader-spinner::after {
            content: '';
            position: absolute;
            inset: 5px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.65);
            box-shadow: inset 0 0 0 1px rgba(226, 232, 240, 0.7);
        }

        .loader-orbit {
            position: absolute;
            inset: 0;
            border-radius: 999px;
            animation: loaderOrbit 0.9s linear infinite;
            pointer-events: none;
        }

        .loader-orbit::before,
        .loader-orbit::after {
            content: '';
            position: absolute;
            top: 1px;
            left: 50%;
            width: 6px;
            height: 6px;
            border-radius: 999px;
            transform: translateX(-50%);
            background: rgba(37, 99, 235, 0.95);
            box-shadow:
                0 0 0 2px rgba(255, 255, 255, 0.85),
                0 10px 22px rgba(37, 99, 235, 0.25);
        }

        .loader-orbit::after {
            top: auto;
            bottom: 1px;
            background: rgba(99, 102, 241, 0.9);
            box-shadow:
                0 0 0 2px rgba(255, 255, 255, 0.8),
                0 10px 22px rgba(99, 102, 241, 0.22);
            opacity: 0.95;
        }

        @keyframes loaderOrbit {
            to {
                transform: rotate(360deg);
            }
        }

        /* Signature motion: Infinity (∞) orbit using offset-path (fallbacks to circular orbit above) */
        @supports (offset-path: path('M0 0')) {
            .loader-orbit {
                animation: none;
            }

            .loader-orbit::before,
            .loader-orbit::after {
                top: 50%;
                bottom: auto;
                left: 50%;
                transform: translate(-50%, -50%);
                offset-path: path('M 11 11 C 6 2, 0 6, 6 11 C 0 16, 6 20, 11 11 C 16 2, 22 6, 16 11 C 22 16, 16 20, 11 11');
                offset-rotate: 0deg;
                animation: loaderInfinity 1.15s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            }

            .loader-orbit::after {
                animation-direction: reverse;
                opacity: 0.9;
            }

            @keyframes loaderInfinity {
                to {
                    offset-distance: 100%;
                }
            }
        }

        @keyframes loaderSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 132px;
        }

        .loader-text {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #0f172a;
            user-select: none;
        }

        .loader-label {
            opacity: 0.95;
        }

        .loader-dots {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .loader-dots span {
            width: 4px;
            height: 4px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.42);
            animation: loaderDot 1.05s ease-in-out infinite;
        }

        .loader-dots span:nth-child(2) {
            background: rgba(99, 102, 241, 0.38);
        }

        .loader-dots span:nth-child(3) {
            background: rgba(15, 23, 42, 0.28);
        }

        .loader-dots span:nth-child(2) {
            animation-delay: 0.14s;
        }

        .loader-dots span:nth-child(3) {
            animation-delay: 0.28s;
        }

        @keyframes loaderDot {

            0%,
            100% {
                transform: translateY(0);
                opacity: 0.35;
            }

            50% {
                transform: translateY(-2px);
                opacity: 0.9;
            }
        }

        .loader-bar {
            height: 3px;
            width: 92px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.28);
            overflow: hidden;
        }

        .loader-bar::before {
            content: '';
            display: block;
            height: 100%;
            width: 55%;
            border-radius: 999px;
            background: linear-gradient(90deg,
                    rgba(37, 99, 235, 0),
                    rgba(37, 99, 235, 0.55),
                    rgba(99, 102, 241, 0.48),
                    rgba(14, 165, 233, 0.42),
                    rgba(37, 99, 235, 0));
            transform: translateX(-80%);
            animation: loaderBar 1.1s ease-in-out infinite;
        }

        @keyframes loaderBar {
            0% {
                transform: translateX(-80%);
            }

            50% {
                transform: translateX(60%);
            }

            100% {
                transform: translateX(180%);
            }
        }

        @media (max-width: 380px) {
            #global-loader {
                padding: 11px 14px 11px 12px;
            }

            .loader-content {
                min-width: 116px;
            }

            .loader-bar {
                width: 82px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            #global-loader,
            #global-loader.active {
                transition: none;
            }

            #global-loader {
                animation: none;
            }

            .loader-spinner::before,
            .loader-orbit,
            .loader-dots span,
            .loader-bar::before {
                animation: none !important;
            }

            .loader-orbit::before,
            .loader-orbit::after {
                animation: none !important;
            }

            .loader-bar::before {
                transform: translateX(0);
                width: 35%;
            }
        }

        @media (min-width: 768px) {
            aside {
                transition: width 0.3s ease-in-out;
            }
            .sidebar-collapsed {
                width: 5.5rem !important; /* 88px */
            }
            .sidebar-collapsed .nav-text {
                opacity: 0;
                max-width: 0;
                margin-left: 0 !important;
                overflow: hidden;
            }
            .sidebar-collapsed .nav-link {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
                width: 3.5rem;
                margin-left: auto;
                margin-right: auto;
                gap: 0;
            }
            .sidebar-collapsed .nav-icon {
                margin: 0;
            }
            .sidebar-collapsed .nav-section-title {
                opacity: 0;
                max-height: 0;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
            .sidebar-collapsed .status-section {
                opacity: 0;
                max-height: 0;
                overflow: hidden;
                padding: 0;
                margin: 0;
            }
            .sidebar-collapsed .footer-text-container {
                opacity: 0;
                max-width: 0;
                overflow: hidden;
                margin: 0;
                padding: 0;
            }
            .sidebar-collapsed .footer-avatar {
                display: none;
            }
            .sidebar-collapsed .footer-btn span {
                display: none;
            }
            .sidebar-collapsed .footer-btn {
                width: 2.5rem;
                justify-content: center;
                padding: 0;
                margin: 0 auto;
                gap: 0;
            }
            
            /* Tooltip on hover */
            .sidebar-collapsed .nav-link:hover::after,
            .sidebar-collapsed .footer-btn:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                left: 100%;
                margin-left: 0.75rem;
                padding: 0.5rem 0.75rem;
                background-color: #1e293b;
                color: white;
                font-size: 0.75rem;
                font-weight: 700;
                border-radius: 0.5rem;
                white-space: nowrap;
                z-index: 100;
                pointer-events: none;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }
            .dark .sidebar-collapsed .nav-link:hover::after,
            .dark .sidebar-collapsed .footer-btn:hover::after {
                background-color: #334155;
            }
        }
        
        .nav-text {
            transition: all 0.3s ease;
            white-space: nowrap;
            max-width: 200px; 
            width: 100%;
            text-align: left;
        }
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-section-title, .status-section, .footer-text-container, .footer-avatar {
            transition: all 0.3s ease;
            max-height: 500px;
        }
    </style>

</head>

@php($isAdmin = Auth::check() && Auth::user()->role === 'admin')

<body class="flex min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300 overflow-x-hidden"
    :class="{ 'dark': darkMode }" x-data="{ 
          mobileMenuOpen: false, 
          darkMode: document.documentElement.classList.contains('dark'),
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          toggleSidebar() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
          },
          toggleTheme() {
              document.documentElement.classList.add('theme-transitioning');
              
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
              }
              
              setTimeout(() => {
                  document.documentElement.classList.remove('theme-transitioning');
              }, 700);
          }
      }">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-[2px] z-[60] md:hidden"></div>

    <!-- Sidebar -->
    @php($classCode = \App\Models\Setting::get('class_code', '07TPLE018'))
    <aside :class="[
            mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
            sidebarCollapsed ? 'sidebar-collapsed' : '{{ $isAdmin ? 'md:w-60' : 'md:w-64' }}'
        ]"
        class="fixed md:sticky top-0 left-0 w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col h-[100dvh] md:h-screen z-[70] transition-all duration-300 ease-in-out md:shadow-none shadow-2xl transform-gpu will-change-transform group">


        <!-- Header -->
        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 relative flex items-center min-h-[72px]" :class="sidebarCollapsed ? 'md:justify-center md:px-0' : ''">
            <div class="flex items-center gap-3 overflow-hidden transition-all duration-300" :class="sidebarCollapsed ? 'md:gap-0' : ''">
                <div class="flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-11 h-11 drop-shadow-md transition-transform duration-300 hover:scale-110"
                        viewBox="0 0 64 64" fill="none">
                        <!-- Main Document Body -->
                        <rect x="14" y="8" width="32" height="44" rx="5" fill="#eff6ff" stroke="#007bff"
                            stroke-width="3.5" />
                        <!-- Document Text Lines -->
                        <line x1="22" y1="22" x2="38" y2="22" stroke="#007bff" stroke-width="3.5"
                            stroke-linecap="round" />
                        <line x1="22" y1="32" x2="32" y2="32" stroke="#007bff" stroke-width="3.5"
                            stroke-linecap="round" />
                        <!-- Approval Badge (Checkmark) -->
                        <circle cx="42" cy="46" r="14" fill="#007bff" stroke="#ffffff" stroke-width="3.5" />
                        <path d="M36.5 46.5 L 40 50 L 47.5 41" stroke="#ffffff" stroke-width="3.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="min-w-0 mt-0.5 whitespace-nowrap transition-all duration-300" :class="sidebarCollapsed ? 'md:max-w-0 md:opacity-0 md:overflow-hidden' : 'max-w-[200px] opacity-100'">
                    <p class="text-[20px] font-black leading-none mb-0.5" style="color: #007bff; letter-spacing: 2px;">
                        SIPEKA</p>
                    <h1 class="text-[11px] font-black text-slate-500 dark:text-slate-400 tracking-tight truncate">
                        {{ $classCode }}</h1>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-h-0 flex flex-col overflow-hidden dark:bg-slate-900">
            <!-- Main Navigation (Student) -->
            @if(Auth::user()->role === 'siswa')
                <nav class="px-3 pt-2 pb-2 space-y-1 overflow-y-auto overflow-x-hidden">
                    <p class="px-2 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 nav-section-title">
                        Navigasi</p>


                    <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" data-tooltip="Dashboard"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Request::is('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-none' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400' }}">

                        <i class="fas fa-house w-5 text-center text-[15px] nav-icon"></i>
                        <span class="font-bold text-sm nav-text">Dashboard</span>
                    </a>

                    @if(Request::is('dashboard'))
                        <button type="button" @click="mobileMenuOpen = false; $dispatch('open-permission-modal')" data-tooltip="Ajukan Izin"
                            class="nav-link w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-slate-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-plus-circle w-5 text-center text-[15px] nav-icon"></i>
                            <span class="font-bold text-sm nav-text">Ajukan Izin</span>
                        </button>
                    @else
                        <a href="{{ route('dashboard') }}?open=izin" @click="mobileMenuOpen = false" data-tooltip="Ajukan Izin"
                            class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-slate-700 hover:bg-blue-50 hover:text-blue-600">
                            <i class="fas fa-plus-circle w-5 text-center text-[15px] nav-icon"></i>
                            <span class="font-bold text-sm nav-text">Ajukan Izin</span>
                        </a>
                    @endif

                    <a href="{{ Request::is('dashboard') ? '#riwayat-pengajuan' : route('dashboard') . '#riwayat-pengajuan' }}"
                        @click="mobileMenuOpen = false" data-tooltip="Riwayat Pengajuan"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-slate-700 hover:bg-blue-50 hover:text-blue-600">
                        <i class="fas fa-history w-5 text-center text-[15px] nav-icon"></i>
                        <span class="font-bold text-sm nav-text">Riwayat Pengajuan</span>
                    </a>

                    <a href="{{ route('lecturers.index') }}" @click="mobileMenuOpen = false" data-tooltip="Kontak Dosen"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Request::is('lecturers*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-none' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <i class="fas fa-address-book w-5 text-center text-[15px] nav-icon"></i>
                        <span class="font-bold text-sm nav-text">Kontak Dosen</span>
                    </a>
                </nav>

                <!-- Status Section -->
                <div class="px-3 pt-2 pb-2 mt-auto status-section">
                    <p class="px-2 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        Status</p>
                    <div
                        class="mt-2 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 px-3 py-2.5">
                        <div class="flex items-start gap-2.5">
                            <div
                                class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center shrink-0">
                                <i class="fab fa-telegram-plane text-[14px] text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 leading-tight">Telegram
                                    </p>
                                    @if(Auth::user()->telegram_chat_id)
                                        <span
                                            class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 leading-none">Connected</span>
                                    @else
                                        <span
                                            class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 leading-none">Aktivasi
                                            Telegram</span>
                                    @endif
                                </div>
                                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">
                                    Notifikasi izin via Telegram.</p>

                                @unless(Auth::user()->telegram_chat_id)
                                    <a href="{{ route('telegram.activate') }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-1.5 mt-1.5 text-[11px] font-black text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-link text-[10px]"></i> Aktivasi Telegram
                                    </a>
                                @endunless
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(Auth::user()->role === 'admin')
                <nav class="flex-1 min-h-0 overflow-y-auto md:overflow-visible px-3 pt-3 pb-6 space-y-1 overflow-x-hidden">
                    <p class="px-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 mt-2 nav-section-title">Operasional
                    </p>
                    <a href="{{ route('dashboard') }}" data-tooltip="Dashboard"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ Request::is('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i class="fas fa-house w-5 text-center text-[14px] nav-icon"></i>
                        <span class="font-bold text-[13px] nav-text">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" data-tooltip="Rekap Izin"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.permissions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i class="fas fa-clipboard-check w-5 text-center text-[14px] nav-icon"></i>
                        <span class="font-bold text-[13px] nav-text">Rekap Izin</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" data-tooltip="Lapor Dosen"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i class="fas fa-file-contract w-5 text-center text-[14px] nav-icon"></i>
                        <span class="font-bold text-[13px] nav-text">Lapor Dosen</span>
                    </a>

                    <!-- Tracking Menu -->
                    <a href="{{ route('admin.tracking.index') }}" data-tooltip="Tracking Live"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.tracking.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <div class="relative w-5 flex justify-center text-[14px] nav-icon">
                            <i class="fas fa-satellite-dish"></i>
                            <span class="absolute -top-1 -right-1 flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        </div>
                        <span class="font-bold text-[13px] nav-text">Tracking Live</span>
                    </a>

                    <p class="px-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 mt-5 nav-section-title">Data Master
                    </p>

                    <a href="{{ route('admin.students.index') }}" data-tooltip="Data Mahasiswa"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ Request::is('admin/students*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i class="fas fa-user-graduate w-5 text-center text-[14px] nav-icon"></i>
                        <span class="font-bold text-[13px] nav-text">Data Mahasiswa</span>
                    </a>
                    <a href="{{ route('admin.subjects.index') }}" data-tooltip="Data Mata Kuliah"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ Request::is('admin/subjects*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i class="fas fa-building-columns w-5 text-center text-[14px] nav-icon"></i>
                        <span class="font-bold text-[13px] nav-text">Data Mata Kuliah</span>
                    </a>
                    <a href="{{ route('admin.lecturers.index') }}" data-tooltip="Kontak Dosen"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ Request::is('admin/lecturers*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i class="fas fa-address-book w-5 text-center text-[14px] nav-icon"></i>
                        <span class="font-bold text-[13px] nav-text">Kontak Dosen</span>
                    </a>

                    <p class="px-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 mt-5 nav-section-title">Sistem</p>

                    <a href="{{ route('admin.users.index') }}" data-tooltip="Kelola Akun"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ Request::is('admin/users*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i class="fas fa-users-cog w-5 text-center text-[14px] nav-icon"></i>
                        <span class="font-bold text-[13px] nav-text">Kelola Akun</span>
                    </a>
                    <!-- Settings Menu -->
                    <a href="{{ route('admin.settings.index') }}" data-tooltip="Pengaturan"
                        class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i class="fas fa-cog w-5 text-center text-[14px] nav-icon"></i>
                        <span class="font-bold text-[13px] nav-text">Pengaturan</span>
                    </a>
                </nav>
            @endif
        </div>

        <!-- Footer -->
        <div class="px-4 py-2 border-t border-slate-100 dark:border-slate-800 shrink-0 dark:bg-slate-900 transition-all duration-300">
            <div class="flex items-center gap-2.5 px-1 w-full transition-all duration-300">
                <div
                    class="footer-avatar w-8 h-8 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-700 dark:text-blue-400 font-black shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="min-w-0 flex-1 footer-text-container">
                    <p class="text-sm font-black text-slate-900 dark:text-slate-200 truncate">
                        {{ \Illuminate\Support\Str::words(Auth::user()->name, 2, '') }}
                    </p>
                    @if(Auth::user()->role === 'siswa' && Auth::user()->nim)
                        <p class="text-[11px] font-bold text-blue-600 dark:text-blue-400 truncate">{{ Auth::user()->nim }}
                        </p>
                    @else
                        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 capitalize">
                            {{ Auth::user()->role }}
                        </p>
                    @endif
                </div>
                <button type="button" onclick="confirmLogout()" aria-label="Keluar" data-tooltip="Keluar"
                    class="footer-btn {{ $isAdmin ? 'h-9' : 'h-10' }} px-3 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors inline-flex items-center gap-2 shrink-0">
                    <i class="fas fa-right-from-bracket {{ $isAdmin ? 'text-[13px]' : 'text-[14px]' }} nav-icon"></i>
                    <span class="text-xs font-black">Keluar</span>
                </button>
            </div>


            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0">
        <header
            class="hidden md:flex bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 {{ $isAdmin ? 'p-3' : 'p-4' }} justify-between items-center sticky top-0 z-10">
            
            <!-- Sidebar Toggle Button -->
            <div class="flex items-center">
                <button @click="toggleSidebar()" 
                    class="w-10 h-10 flex items-center justify-center bg-transparent text-slate-600 dark:text-slate-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-blue-600 transition-all focus:outline-none">
                    <i class="fas fa-bars-staggered text-[16px] transition-transform duration-300" :class="sidebarCollapsed ? '' : 'rotate-180'"></i>
                </button>
            </div>

            <div class="flex items-center gap-4">
                <!-- Theme Toggle -->
                <button @click="toggleTheme" aria-label="Toggle Theme"
                    class="relative flex items-center justify-center w-10 h-10 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm">
                    <i x-show="darkMode" x-cloak class="fa-solid fa-sun absolute text-amber-400 text-lg"
                        x-transition:enter="transition-all duration-700 ease-in-out"
                        x-transition:enter-start="opacity-0 -rotate-90 scale-50"
                        x-transition:enter-end="opacity-100 rotate-0 scale-100"
                        x-transition:leave="transition-all duration-700 ease-in-out absolute"
                        x-transition:leave-start="opacity-100 rotate-0 scale-100"
                        x-transition:leave-end="opacity-0 rotate-90 scale-50"></i>

                    <i x-show="!darkMode" x-cloak class="fa-solid fa-moon absolute text-indigo-500 text-lg"
                        x-transition:enter="transition-all duration-700 ease-in-out"
                        x-transition:enter-start="opacity-0 rotate-90 scale-50"
                        x-transition:enter-end="opacity-100 rotate-0 scale-100"
                        x-transition:leave="transition-all duration-700 ease-in-out absolute"
                        x-transition:leave-start="opacity-100 rotate-0 scale-100"
                        x-transition:leave-end="opacity-0 -rotate-90 scale-50"></i>
                </button>
                <div class="h-8 w-px bg-gray-200 dark:bg-slate-700"></div>
                <button class="p-2 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-300"><i
                        class="far fa-bell {{ $isAdmin ? 'text-lg' : 'text-xl' }}"></i></button>
                <div class="h-8 w-px bg-gray-200 dark:bg-slate-700"></div>
                <span
                    class="{{ $isAdmin ? 'text-xs' : 'text-sm' }} text-gray-600 dark:text-slate-400">{{ date('l, d F Y') }}</span>
            </div>
        </header>


        <!-- Mobile Header -->
        <header
            class="md:hidden bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 p-3 flex justify-between items-center sticky top-0 z-50">
            <div class="flex items-center gap-3">
                <button @click="mobileMenuOpen = true"
                    class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div class="min-w-0 mt-0.5">
                    <p class="text-[18px] font-black leading-none mb-0.5"
                        style="color: #007bff; letter-spacing: 1.5px;">SIPEKA</p>
                    <h1 class="text-[10px] font-black text-slate-500 dark:text-slate-400 tracking-tight truncate">
                        {{ $classCode }}
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="toggleTheme" aria-label="Toggle Theme"
                    class="relative flex items-center justify-center w-10 h-10 transition-all duration-300 hover:scale-110 active:scale-95 drop-shadow-sm">
                    <i x-show="darkMode" x-cloak class="fa-solid fa-sun absolute text-amber-400 text-lg"
                        x-transition:enter="transition-all duration-700 ease-in-out"
                        x-transition:enter-start="opacity-0 -rotate-90 scale-50"
                        x-transition:enter-end="opacity-100 rotate-0 scale-100"
                        x-transition:leave="transition-all duration-700 ease-in-out absolute"
                        x-transition:leave-start="opacity-100 rotate-0 scale-100"
                        x-transition:leave-end="opacity-0 rotate-90 scale-50"></i>

                    <i x-show="!darkMode" x-cloak class="fa-solid fa-moon absolute text-indigo-500 text-lg"
                        x-transition:enter="transition-all duration-700 ease-in-out"
                        x-transition:enter-start="opacity-0 rotate-90 scale-50"
                        x-transition:enter-end="opacity-100 rotate-0 scale-100"
                        x-transition:leave="transition-all duration-700 ease-in-out absolute"
                        x-transition:leave-start="opacity-100 rotate-0 scale-100"
                        x-transition:leave-end="opacity-0 -rotate-90 scale-50"></i>
                </button>
                <div
                    class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-lg shadow-blue-100 dark:shadow-none text-xs">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>


        <div class="p-4 {{ $isAdmin ? 'md:p-6' : 'md:p-8' }}">
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center gap-3 animate-bounce">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function fireAppSwal(options) {
            const base = {
                buttonsStyling: false,
                customClass: {
                    popup: 'app-swal rounded-2xl',
                    title: 'app-swal-title',
                    htmlContainer: 'app-swal-text',
                    icon: 'app-swal-icon',
                    actions: 'app-swal-actions',
                    confirmButton: 'app-swal-btn app-swal-btn--primary',
                    cancelButton: 'app-swal-btn app-swal-btn--neutral',
                },
            };

            const customClass = { ...base.customClass, ...(options.customClass || {}) };

            return Swal.fire({ ...base, ...options, customClass });
        }

        function confirmLogout() {
            fireAppSwal({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sesi ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const loader = document.getElementById('global-loader');
                    if (loader) loader.classList.add('active');
                    document.getElementById('logout-form').submit();
                }
            })
        }
        function confirmDelete(formId, message = "Data ini akan dihapus secara permanen!") {
            fireAppSwal({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'app-swal-btn app-swal-btn--danger',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const loader = document.getElementById('global-loader');
                    if (loader) loader.classList.add('active');
                    document.getElementById(formId).submit();
                }
            })
        }

        @if(session('error'))
            fireAppSwal({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
            });
        @endif
    </script>
    @stack('scripts')

    <!-- Global Floating Loader -->
    <div id="global-loader" role="status" aria-live="polite" aria-label="Memproses">
        <div class="loader-spinner" aria-hidden="true"><span class="loader-orbit"></span></div>
        <div class="loader-content">
            <span class="loader-text"><span class="loader-label">Memproses</span>
                <span class="loader-dots" aria-hidden="true"><span></span><span></span><span></span></span>
            </span>
            <span class="loader-bar" aria-hidden="true"></span>
        </div>
    </div>

    <script>
        (function () {
            const loader = document.getElementById('global-loader');
            if (!loader) return;

            function showLoader() {
                loader.classList.add('active');
            }
            function hideLoader() {
                loader.classList.remove('active');
            }

            // 1. Page Navigation — intercept link clicks
            document.addEventListener('click', function (e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                // Skip: new tabs, anchors, javascript:, Alpine toggles, external links
                if (link.target === '_blank' || link.hasAttribute('download')) return;
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
                if (link.hasAttribute('@click') || link.hasAttribute('x-on:click')) return;
                // Only internal links
                if (href.startsWith('http') && !href.startsWith(window.location.origin)) return;
                showLoader();
            });

            // 2. Form Submissions
            document.addEventListener('submit', function (e) {
                const form = e.target;
                // Skip forms handled by Alpine's @submit.prevent (they won't actually submit)
                if (form.hasAttribute('@submit.prevent') || form.hasAttribute('x-on:submit.prevent')) return;
                showLoader();
            });

            // 3. Intercept Fetch API
            const origFetch = window.fetch;
            let fetchCount = 0;
            window.fetch = function () {
                const url = arguments[0];
                const isPolling = typeof url === 'string' && url.includes('session/check');

                if (!isPolling) {
                    fetchCount++;
                    showLoader();
                }

                return origFetch.apply(this, arguments).finally(function () {
                    if (!isPolling) {
                        fetchCount--;
                        if (fetchCount <= 0) { fetchCount = 0; hideLoader(); }
                    }
                });
            };

            // 5. Hide on page show (back/forward cache)
            window.addEventListener('pageshow', function () {
                hideLoader();
            });

            // 6. Safety: hide after timeout to prevent stuck loader
            let safetyTimer = null;
            const origShow = showLoader;
            showLoader = function () {
                origShow();
                clearTimeout(safetyTimer);
                safetyTimer = setTimeout(hideLoader, 15000);
            };
        })();
    </script>

    @auth
        @if(auth()->user()->role === 'siswa')
            <script>
                (function () {
                    let isActiveStatus = true;
                    setInterval(() => {
                        fetch('{{ route('session.check') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.authenticated && data.is_active === false && isActiveStatus) {
                                    isActiveStatus = false;

                                    Swal.fire({
                                        title: 'Akses Dicabut!',
                                        text: 'Akun Anda baru saja dinonaktifkan oleh Admin. Anda akan dikeluarkan dari sistem.',
                                        icon: 'error',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        allowOutsideClick: false,
                                        allowEscapeKey: false
                                    }).then(() => {
                                        document.getElementById('logout-form').submit();
                                    });
                                }
                            })
                            .catch(err => console.error('Session check error', err));
                    }, 15000); // Check every 15 seconds
                })();
            </script>
        @endif
    @endauth
</body>

</html>