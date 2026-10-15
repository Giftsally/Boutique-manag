<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | Boutique Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Manrope', sans-serif;
        }

        .login-background {
            background:
                radial-gradient(circle at 15% 20%, rgba(16, 185, 129, 0.12), transparent 32%),
                radial-gradient(circle at 90% 85%, rgba(16, 185, 129, 0.07), transparent 28%),
                #f5f7f6;
        }

        .brand-panel {
            background:
                radial-gradient(circle at 85% 15%, rgba(16, 185, 129, 0.17), transparent 30%),
                linear-gradient(145deg, #10251f 0%, #0b1714 65%, #101e19 100%);
        }

        .login-input {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .login-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
            outline: none;
        }

        .login-button {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .login-button:hover {
            background-color: #047857;
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="login-background min-h-screen antialiased text-gray-800">

    <main class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-10">

        <div class="grid w-full max-w-6xl overflow-hidden rounded-3xl bg-white shadow-2xl shadow-gray-200/70 lg:min-h-[680px] lg:grid-cols-2">

            <!-- Left branding panel -->
            <section class="brand-panel relative hidden flex-col justify-between overflow-hidden p-10 text-white lg:flex xl:p-14">

                <!-- Decorative circles -->
                <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -right-12 -top-12 h-56 w-56 rounded-full border border-emerald-400/20"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-20 h-80 w-80 rounded-full border border-white/10"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-950/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 8.5 12 4l8 4.5v7L12 20l-8-4.5v-7Z" stroke-linejoin="round"/>
                                <path d="m4.5 8.7 7.5 4.4 7.5-4.4M12 13.1V20" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-lg font-extrabold tracking-tight">Boutique Management</h2>
                            <p class="mt-0.5 text-xs tracking-widest text-emerald-300">BUSINESS CONTROL CENTER</p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 max-w-md">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-4 py-2 text-xs font-semibold text-emerald-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        YOUR BUSINESS, ORGANIZED
                    </div>

                    <h1 class="text-4xl font-extrabold leading-tight tracking-tight xl:text-5xl">
                        Manage your business with
                        <span class="text-emerald-400">clarity.</span>
                    </h1>

                    <p class="mt-6 max-w-sm text-base leading-7 text-gray-300">
                        Keep track of customers, purchases, payments, and outstanding balances from one organized workspace.
                    </p>

                    <div class="mt-10 space-y-5">
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M3 3v18h18"/>
                                    <path d="m7 14 4-4 4 3 5-7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Monitor your records</p>
                                <p class="mt-1 text-sm text-gray-400">Keep business transactions organized.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="M3 10h18M7 15h3"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Track payments</p>
                                <p class="mt-1 text-sm text-gray-400">Know what has been paid and what remains.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3"/>
                                    <path d="M16 3h5v5M10 14 21 3"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Generate reports</p>
                                <p class="mt-1 text-sm text-gray-400">Review your business activity with ease.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 border-t border-white/10 pt-6">
                    <p class="text-xs text-gray-400">
                        A simpler way to manage your daily business operations.
                    </p>
                </div>
            </section>

            <!-- Right login panel -->
            <section class="flex items-center justify-center px-6 py-10 sm:px-10 lg:px-12 xl:px-16">

                <div class="w-full max-w-md">

                    <!-- Mobile branding -->
                    <div class="mb-10 flex items-center gap-3 lg:hidden">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 8.5 12 4l8 4.5v7L12 20l-8-4.5v-7Z" stroke-linejoin="round"/>
                                <path d="m4.5 8.7 7.5 4.4 7.5-4.4M12 13.1V20" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-extrabold tracking-tight text-gray-900">Boutique Management</h2>
                            <p class="text-xs text-gray-500">BUSINESS CONTROL CENTER</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.18em] text-emerald-700">
                            Welcome back
                        </p>

                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Sign in to your account
                        </h1>

                        <p class="mt-3 text-sm leading-6 text-gray-500">
                            Enter your credentials to access your management dashboard.
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status
                        class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
                        :status="session('status')"
                    />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                                Email address
                            </label>

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <path d="m3 7 9 6 9-6"/>
                                    </svg>
                                </div>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="you@example.com"
                                    class="login-input block w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-12 pr-4 text-sm text-gray-900 placeholder:text-gray-400"
                                >
                            </div>

                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label for="password" class="block text-sm font-semibold text-gray-700">
                                    Password
                                </label>

                                @if (Route::has('password.request'))
                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-800 hover:underline"
                                    >
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="4" y="10" width="16" height="11" rx="2"/>
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/>
                                    </svg>
                                </div>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="login-input block w-full rounded-xl border border-gray-200 bg-gray-50 py-3.5 pl-12 pr-4 text-sm text-gray-900 placeholder:text-gray-400"
                                >
                            </div>

                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                value="1"
                                @checked(old('remember'))
                                class="h-4 w-4 rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                            >

                            <label for="remember_me" class="ml-3 text-sm text-gray-600">
                                Keep me signed in
                            </label>
                        </div>

                        <!-- Submit -->
                        <button
                            type="submit"
                            class="login-button flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            Sign in
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </form>

                    <div class="mt-8 border-t border-gray-100 pt-6 text-center">
                        <p class="text-xs leading-5 text-gray-400">
                            Authorized access only. Please keep your login credentials secure.
                        </p>
                    </div>

                </div>
            </section>
        </div>
    </main>

</body>
</html>