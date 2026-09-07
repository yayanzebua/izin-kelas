<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Login - SIPEKA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .login-bg {
            background-image: url('{{ asset('assets/images/backgroundlogin.png') }}');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
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

        .btn-sipeka {
            background-color: #007bff;
            transition: all 0.3s ease;
        }

        .btn-sipeka:hover {
            background-color: #0069d9;
            box-shadow: 0 4px 14px 0 rgba(0, 123, 255, 0.39);
        }
    </style>
</head>

<body x-data="loginController()"
    class="min-h-screen px-4 py-10 flex items-center justify-center overflow-x-hidden transition-colors duration-500 bg-slate-50">



    <!-- Background: Professional Background Image with subtle overlay -->
    <div class="fixed inset-0 -z-30 login-bg scale-105 transition-all duration-700"></div>
    <div class="fixed inset-0 -z-20 bg-slate-900/10 backdrop-blur-[2px] transition-colors duration-700">
    </div>
    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-blue-500/5 to-indigo-500/10 pointer-events-none">
    </div>

    <div class="w-full max-w-[22rem] flex flex-col items-stretch gap-4">
        <div
            class="glass-card rounded-3xl overflow-hidden relative bg-white/80 backdrop-blur-2xl border border-white shadow-2xl shadow-slate-900/10 transition-all duration-500">
            <!-- Subtle decoration (kept but toned down) -->
            <div class="absolute -top-10 -right-10 w-28 h-28 bg-blue-500/4 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-12 -left-12 w-28 h-28 bg-indigo-500/4 rounded-full blur-3xl"></div>

            <!-- Branding / Header -->
            <div class="px-6 pt-12 pb-1 text-center">
                <div class="mx-auto mb-2 flex items-center justify-center relative w-20 h-20">
                    <!-- Default Document Logo -->
                    <svg x-show="!isFocused" x-transition:enter="transition ease-out duration-300 delay-75"
                        x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200 absolute"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-50"
                        xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 drop-shadow-md absolute inset-0"
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

                    <!-- Interactive Mascot Logo (3D Human Avatar) -->
                    <svg x-show="isFocused" style="display: none;"
                        x-transition:enter="transition ease-out duration-300 delay-75"
                        x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200 absolute"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-50"
                        viewBox="0 0 200 200" class="w-20 h-20 drop-shadow-md absolute inset-0" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <clipPath id="circle-clip">
                                <circle cx="100" cy="100" r="100" />
                            </clipPath>
                            <linearGradient id="skinGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#FDE0C1" />
                                <stop offset="100%" stop-color="#F4CBAB" />
                            </linearGradient>
                            <linearGradient id="shirtGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#60A5FA" />
                                <stop offset="100%" stop-color="#2563EB" />
                            </linearGradient>
                            <linearGradient id="hairGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#1E293B" />
                                <stop offset="100%" stop-color="#0F172A" />
                            </linearGradient>
                            <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="4" stdDeviation="4" flood-opacity="0.15" />
                            </filter>
                        </defs>

                        <g clip-path="url(#circle-clip)">
                            <!-- Background Circle -->
                            <circle cx="100" cy="100" r="100" fill="#E0F2FE" />

                            <!-- Shoulders / Shirt -->
                            <path d="M30 200 C30 130 170 130 170 200" fill="url(#shirtGrad)" />

                            <!-- Neck -->
                            <rect x="85" y="120" width="30" height="30" fill="url(#skinGrad)" filter="url(#shadow)" />
                            <path d="M85 120 Q100 140 115 120" fill="#E0B796" opacity="0.6" />

                            <!-- Head/Face (Oval) -->
                            <rect x="58" y="45" width="84" height="100" rx="42" fill="url(#skinGrad)"
                                filter="url(#shadow)" />

                            <!-- Ears -->
                            <circle cx="55" cy="105" r="10" fill="url(#skinGrad)" />
                            <circle cx="145" cy="105" r="10" fill="url(#skinGrad)" />

                            <!-- Hair Base (Back/Top) -->
                            <path d="M55 80 C50 30 150 30 145 80 C145 100 135 60 100 50 C65 60 55 100 55 80 Z"
                                fill="url(#hairGrad)" filter="url(#shadow)" />
                            <!-- Front Hair / Bangs -->
                            <path d="M56 70 C70 45 110 50 140 75 C125 55 90 60 70 85 C65 75 60 75 56 70 Z"
                                fill="#334155" />

                            <!-- Eyebrows -->
                            <path d="M70 90 Q80 86 88 90" stroke="#0F172A" stroke-width="4" stroke-linecap="round"
                                fill="none" />
                            <path d="M130 90 Q120 86 112 90" stroke="#0F172A" stroke-width="4" stroke-linecap="round"
                                fill="none" />

                            <!-- White part of eyes -->
                            <circle cx="78" cy="104" r="9" fill="#ffffff" filter="url(#shadow)" />
                            <circle cx="122" cy="104" r="9" fill="#ffffff" filter="url(#shadow)" />

                            <!-- Pupils (Bound to AlpineJS) -->
                            <g
                                :style="`transform: translate(${eyeX}px, ${eyeY}px); transition: transform 0.1s ease-out;`">
                                <circle cx="78" cy="104" r="4.5" fill="#0F172A" />
                                <circle cx="122" cy="104" r="4.5" fill="#0F172A" />
                                <!-- Eye highlights (adds 3D effect) -->
                                <circle cx="76.5" cy="102.5" r="1.5" fill="#ffffff" />
                                <circle cx="120.5" cy="102.5" r="1.5" fill="#ffffff" />
                            </g>

                            <!-- Nose -->
                            <path d="M100 112 L96 122 Q100 126 104 122 Z" fill="#E0B796" />

                            <!-- Mouth -->
                            <path d="M92 134 Q100 142 108 134" stroke="#9A3412" stroke-width="3" stroke-linecap="round"
                                fill="none" />

                            <!-- Hands (Covering Eyes) -->
                            <g
                                :style="`transform: translateY(${isPassword ? (showPass ? '40px' : '0px') : '140px'}); transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);`">
                                <!-- Left Arm -->
                                <path d="M30 220 Q40 160 76 114" stroke="url(#shirtGrad)" stroke-width="22"
                                    stroke-linecap="round" fill="none" filter="url(#shadow)" />
                                <!-- Left Hand -->
                                <circle cx="78" cy="108" r="18" fill="url(#skinGrad)" filter="url(#shadow)" />
                                <!-- Fingers -->
                                <path d="M70 95 L70 110 M78 92 L78 110 M86 95 L86 110" stroke="#E0B796"
                                    stroke-width="2.5" stroke-linecap="round" />

                                <!-- Right Arm -->
                                <path d="M170 220 Q160 160 124 114" stroke="url(#shirtGrad)" stroke-width="22"
                                    stroke-linecap="round" fill="none" filter="url(#shadow)" />
                                <!-- Right Hand -->
                                <circle cx="122" cy="108" r="18" fill="url(#skinGrad)" filter="url(#shadow)" />
                                <!-- Fingers -->
                                <path d="M114 95 L114 110 M122 92 L122 110 M130 95 L130 110" stroke="#E0B796"
                                    stroke-width="2.5" stroke-linecap="round" />
                            </g>
                        </g>
                    </svg>
                </div>
                <p class="font-black mt-4 mb-2.5"
                    style="font-size: 38px; color: #007bff; letter-spacing: 6px; line-height: 1;">SIPEKA</p>
                <h1 class="mb-0 font-bold text-slate-500 uppercase" style="font-size: 11px; letter-spacing: 2.5px;">
                    Sistem Perizinan Kelas
                </h1>
            </div>

            <form action="{{ route('login') }}" method="POST" class="px-6 pb-6 space-y-3.5">
                @csrf

                <!-- Username / NIM -->
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-700 ml-1">Username / NIM</label>
                    <div class="relative group">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-user text-xs" aria-hidden="true"></i>
                        </span>
                        <input type="text" name="login_id" required value="{{ old('login_id') }}"
                            autocomplete="username" autocapitalize="none" spellcheck="false" @input="watchUsername"
                            @focus="watchUsername" @blur="resetEyes"
                            class="h-12 w-full pl-11 pr-4 bg-white/50 border border-slate-200/50 rounded-xl outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all text-base sm:text-sm text-slate-800 font-medium placeholder:text-slate-500"
                            placeholder="Masukkan Username / NIM...">
                    </div>
                    @error('login_id')
                        <div class="mt-1 px-3 py-2 bg-red-50 text-red-700 rounded-xl border border-red-100">
                            <span class="text-xs font-semibold">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-700 ml-1">Password</label>
                    <div class="relative group">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-lock text-xs" aria-hidden="true"></i>
                        </span>
                        <input :type="showPass ? 'text' : 'password'" name="password" required
                            autocomplete="current-password" @focus="coverEyes" @blur="resetEyes"
                            class="h-12 w-full pl-11 pr-12 bg-white/50 border border-slate-200/50 rounded-xl outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all text-base sm:text-sm text-slate-800 font-medium placeholder:text-slate-500"
                            placeholder="Masukkan Password...">
                        <button type="button" @click="showPass = !showPass"
                            class="absolute inset-y-0 right-0 w-12 flex items-center justify-center text-slate-500 hover:text-blue-600 transition-colors rounded-xl focus-visible:outline-none"
                            aria-label="Tampilkan atau sembunyikan password">
                            <i class="fas text-sm transition-all duration-300"
                                :class="showPass ? 'fa-eye-slash text-blue-600' : 'fa-eye'" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="btn-sipeka mt-2 w-full flex items-center justify-center gap-2 h-12 rounded-xl text-white font-bold text-sm tracking-wider hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fas fa-sign-in-alt text-base"></i>
                    Masuk
                </button>
            </form>
        </div>

        <!-- Footer (subtle) -->
        <div class="text-center">
            <p class="text-[10px] font-bold text-slate-600/80 tracking-widest uppercase">
                © 2026 Yayan Zebua • All Rights Reserved
            </p>
        </div>
    </div>

    @if($errors->any())
        <script>
            // Simple shake effect for error
            const card = document.querySelector('.glass-card');
            card.animate([
                { transform: 'translateX(0)' },
                { transform: 'translateX(-10px)' },
                { transform: 'translateX(10px)' },
                { transform: 'translateX(-10px)' },
                { transform: 'translateX(10px)' },
                { transform: 'translateX(0)' }
            ], { duration: 400 });
        </script>
    @endif

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
        function loginController() {
            return {
                showPass: false,
                eyeX: 0,
                eyeY: 0,
                isPassword: false,
                isFocused: false,

                init() {
                    // Mengunci tinggi body saat pertama kali load agar tidak melompat ketika keyboard HP muncul
                    this.$el.style.minHeight = window.innerHeight + 'px';
                },

                watchUsername(e) {
                    this.isFocused = true;
                    this.isPassword = false;
                    let length = e.target ? e.target.value.length : 0;
                    let maxChars = 25;
                    let ratio = Math.min(length / maxChars, 1);
                    // Move pupils from left (-10) to right (+10)
                    this.eyeX = -10 + (ratio * 20);
                    // Move pupils down to look at the input field
                    this.eyeY = 6;
                },

                resetEyes() {
                    // Slight delay to prevent flashing if switching between inputs
                    setTimeout(() => {
                        if (!document.activeElement.name || !['login_id', 'password'].includes(document.activeElement.name)) {
                            this.isPassword = false;
                            this.isFocused = false;
                            this.eyeX = 0;
                            this.eyeY = 0;
                        }
                    }, 50);
                },

                coverEyes() {
                    this.isFocused = true;
                    this.isPassword = true;
                    this.eyeX = 0;
                    this.eyeY = 0;
                }
            }
        }
        (function () {
            const loader = document.getElementById('global-loader');
            if (!loader) return;

            function showLoader() {
                loader.classList.add('active');
            }
            function hideLoader() {
                loader.classList.remove('active');
            }

            // Form Submissions
            document.addEventListener('submit', function (e) {
                showLoader();
            });

            // Link clicks
            document.addEventListener('click', function (e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (link.target === '_blank' || link.hasAttribute('download')) return;
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
                if (href.startsWith('http') && !href.startsWith(window.location.origin)) return;
                showLoader();
            });

            // Hide on page show (back/forward cache)
            window.addEventListener('pageshow', function () {
                hideLoader();
            });

            // Safety timeout
            let safetyTimer = null;
            const origShow = showLoader;
            showLoader = function () {
                origShow();
                clearTimeout(safetyTimer);
                safetyTimer = setTimeout(hideLoader, 15000);
            };
        })();
    </script>
</body>

</html>