<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $globalSettings['app_name'] ?? config('app.name', 'SIM Kendaraan') }}</title>

    @if(!empty($globalSettings['app_logo']))
        <link rel="icon" href="{{ asset('storage/' . ltrim($globalSettings['app_logo'], '/')) }}">
    @endif

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <style>
        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: #0f1117;
        }

        /* Animated gradient background */
        .auth-bg {
            background: linear-gradient(135deg, #1145d4 0%, #6d28d9 50%, #1145d4 100%);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            animation: float 6s ease-in-out infinite;
        }

        .orb-1 {
            width: 300px;
            height: 300px;
            background: #818cf8;
            top: -80px;
            left: -80px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 200px;
            height: 200px;
            background: #a78bfa;
            bottom: 20%;
            right: -60px;
            animation-delay: 2s;
        }

        .orb-3 {
            width: 150px;
            height: 150px;
            background: #60a5fa;
            bottom: -40px;
            left: 30%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Stats card */
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Form input style */
        .auth-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }

        .auth-input:focus {
            border-color: #1145d4;
            background: rgba(17, 69, 212, 0.1);
            box-shadow: 0 0 0 3px rgba(17, 69, 212, 0.2);
        }

        .auth-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .auth-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 20px;
        }

        .auth-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #1145d4, #6d28d9);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(17, 69, 212, 0.5);
        }

        .auth-btn:active {
            transform: translateY(0);
        }

        .auth-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }

        .auth-link {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: #a5b4fc;
        }

        .auth-error {
            color: #f87171;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Panel kanan */
        .auth-right-panel {
            background: #0f1117;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 32px;
            min-height: 100vh;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: rgba(255, 255, 255, 0.2);
            font-size: 12px;
        }

        .auth-divider-line {
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-left-panel {
                display: none !important;
            }

            .auth-right-panel {
                padding: 32px 24px;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <div style="display: flex; min-height: 100vh;">

        <!-- ===== Panel Kiri: Branding ===== -->
        <div class="auth-left-panel auth-bg"
            style="flex: 1; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 60px 48px;">
            <!-- Orbs -->
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            <!-- Dot Grid Pattern -->
            <div style="position: absolute; inset: 0; opacity: 0.08; pointer-events: none;"
                style2="background-image: radial-gradient(circle at 2px 2px, white 1.5px, transparent 0); background-size: 32px 32px;">
            </div>

            <!-- Content -->
            <div style="position: relative; z-index: 10; max-width: 420px; text-align: center; color: white;">
                <!-- Logo -->
                <div style="margin-bottom: 32px;">
                    @if(!empty($globalSettings['app_logo']))
                        <img src="{{ asset('storage/' . ltrim($globalSettings['app_logo'], '/')) }}" alt="Logo"
                            style="width: 72px; height: 72px; border-radius: 20px; object-fit: contain; background: white; padding: 8px; margin: 0 auto; display: block; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                    @else
                        <div
                            style="width: 72px; height: 72px; border-radius: 20px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.3); margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined"
                                style="font-size: 36px; color: white;">directions_car</span>
                        </div>
                    @endif
                </div>

                <h1 style="font-size: 28px; font-weight: 900; margin: 0 0 12px; line-height: 1.2;">
                    {{ $globalSettings['app_name'] ?? 'SIM Kendaraan' }}
                </h1>
                <p style="font-size: 15px; opacity: 0.8; line-height: 1.7; margin: 0 0 40px;">
                    Platform digital untuk memantau dan mengelola seluruh armada kendaraan dinas dalam satu dasbor
                    terpusat.
                </p>

                <!-- Stats Cards -->
                <div style="display: flex; flex-direction: column; gap: 12px; text-align: left;">
                    <div class="stat-card">
                        <div
                            style="width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <span class="material-symbols-outlined"
                                style="color: white; font-size: 20px;">directions_car</span>
                        </div>
                        <div>
                            <p style="margin: 0; color: white; font-weight: 700; font-size: 14px;">Manajemen Kendaraan
                            </p>
                            <p style="margin: 0; color: rgba(255,255,255,0.6); font-size: 12px;">Pantau seluruh armada
                                dinas Anda</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div
                            style="width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <span class="material-symbols-outlined"
                                style="color: white; font-size: 20px;">analytics</span>
                        </div>
                        <div>
                            <p style="margin: 0; color: white; font-weight: 700; font-size: 14px;">Laporan Realisasi</p>
                            <p style="margin: 0; color: rgba(255,255,255,0.6); font-size: 12px;">Export PDF & Excel
                                otomatis</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div
                            style="width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <span class="material-symbols-outlined"
                                style="color: white; font-size: 20px;">account_balance_wallet</span>
                        </div>
                        <div>
                            <p style="margin: 0; color: white; font-weight: 700; font-size: 14px;">Monitor Anggaran</p>
                            <p style="margin: 0; color: rgba(255,255,255,0.6); font-size: 12px;">Realisasi vs pagu
                                real-time</p>
                        </div>
                    </div>
                </div>

                <!-- Footer branding -->
                <p style="margin-top: 40px; font-size: 12px; opacity: 0.5;">
                    &copy; {{ date('Y') }} {{ $globalSettings['app_name'] ?? 'SIM Kendaraan' }}
                </p>
            </div>
        </div>

        <!-- ===== Panel Kanan: Form ===== -->
        <div class="auth-right-panel" style="width: 100%; max-width: 500px;">
            <div class="auth-card">
                <!-- Back to home link -->
                <a href="{{ url('/') }}"
                    style="display: inline-flex; align-items: center; gap: 6px; color: rgba(255,255,255,0.4); font-size: 13px; text-decoration: none; margin-bottom: 32px; transition: color .2s; hover: color: white;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
                    Kembali ke Beranda
                </a>

                {{ $slot }}
            </div>
        </div>

    </div>
    @livewireScripts
</body>

</html>