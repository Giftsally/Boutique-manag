
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Boutique Manager</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    <div class="min-h-screen md:flex">

        <!-- Sidebar -->
        <aside class="w-full bg-slate-900 text-white md:min-h-screen md:w-64 md:shrink-0">
            <div class="border-b border-slate-800 px-6 py-6">
                <h1 class="text-xl font-bold tracking-tight">
                    Boutique<span class="text-emerald-400">Manager</span>
                </h1>
                <p class="mt-1 text-xs text-slate-400">
                    Record Management System
                </p>
            </div>

            <nav class="space-y-1 p-4">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center rounded-lg bg-emerald-500/15 px-4 py-3 text-sm font-medium text-emerald-400">
                    Dashboard
                </a>

                <div class="px-4 pb-1 pt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Management
                </div>

               
            <a
                href="{{ route('customers.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                {{ request()->routeIs('customers.*')
                    ? 'bg-slate-800 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            >
                <span>Customers</span>
            </a>


            <a
                href="{{ route('purchases.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                {{ request()->routeIs('purchases.*')
                    ? 'bg-slate-800 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            >
                <span>Purchases</span>
            </a>

          
                <a
                    href="{{ route('payments.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                    {{ request()->routeIs('payments.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <span>Payments</span>
                </a>

             
<a href="{{ route('reports.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition
   {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
    <svg xmlns="http://www.w3.org/2000/svg"
         width="20" height="20" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 3v18h18"/>
        <path d="M18 17V9"/>
        <path d="M13 17V5"/>
        <path d="M8 17v-3"/>
    </svg>
    <span>Reports</span>
</a>
            </nav>

            <div class="border-t border-slate-800 p-4">
                <p class="text-xs text-slate-400">Signed in as</p>
                <p class="mt-1 truncate text-sm font-medium">
                    {{ auth()->user()->name }}
                </p>
            </div>
        </aside>

        <!-- Main content -->
        <div class="min-w-0 flex-1">
            <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-6 py-4 lg:px-8">
                <div>
                    <h2 class="font-semibold text-slate-900">
                        @yield('page-heading', 'Dashboard')
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Manage your boutique operations
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="hidden text-sm text-slate-600 sm:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium hover:bg-slate-50">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-5 lg:p-8">
                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
