
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-heading', 'Overview')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
            Welcome back, {{ auth()->user()->name }}
        </h1>
        <p class="mt-2 text-sm text-slate-500">
            Here is an overview of your boutique business.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Customers</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">0</p>
            <p class="mt-2 text-xs text-slate-400">Registered customers</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Purchases</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">0</p>
            <p class="mt-2 text-xs text-slate-400">Recorded purchases</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Payments Received</p>
            <p class="mt-4 text-3xl font-bold text-emerald-600">₦0.00</p>
            <p class="mt-2 text-xs text-slate-400">Total payments collected</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Outstanding Balance</p>
            <p class="mt-4 text-3xl font-bold text-rose-600">₦0.00</p>
            <p class="mt-2 text-xs text-slate-400">Unpaid purchase balances</p>
        </div>

    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-6 xl:col-span-2">
            <h2 class="font-semibold text-slate-900">Recent Activity</h2>
            <p class="mt-2 text-sm text-slate-500">
                Recent purchases and payments will appear here.
            </p>

            <div class="mt-6 rounded-lg border border-dashed border-slate-300 p-8 text-center">
                <p class="text-sm text-slate-500">
                    No activity to display yet.
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6">
            <h2 class="font-semibold text-slate-900">Quick Actions</h2>

            <div class="mt-5 space-y-3">
                <p class="rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    Register a customer
                </p>

                <p class="rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    Record a purchase
                </p>

                <p class="rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    Record an installment payment
                </p>
            </div>
        </div>

    </div>

@endsection
