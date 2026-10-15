@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" id="report-content">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Business Reports</h1>
            <p class="text-sm text-gray-500 mt-1">
                Review purchases, payments received, and outstanding balances.
            </p>
        </div>

        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-700"
        >
            Print Report
        </button>
    </div>

    {{-- Date Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 print:hidden">
        <form action="{{ route('reports.index') }}" method="GET"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                    Start Date
                </label>
                <input
                    type="date"
                    name="start_date"
                    id="start_date"
                    value="{{ $startDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                    End Date
                </label>
                <input
                    type="date"
                    name="end_date"
                    id="end_date"
                    value="{{ $endDate }}"
                    min="{{ $startDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div class="flex items-end">
                <button
                    type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Apply Filters
                </button>
            </div>

            <div class="flex items-end">
                <a
                    href="{{ route('reports.index') }}"
                    class="w-full text-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500">Purchase Value</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">
                ₦{{ number_format((float) $totalPurchaseValue, 2) }}
            </p>
            <p class="text-xs text-gray-500 mt-2">
                Purchases within the selected date range
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500">Payments Received</p>
            <p class="text-2xl font-bold text-green-700 mt-2">
                ₦{{ number_format((float) $totalPaymentsReceived, 2) }}
            </p>
            <p class="text-xs text-gray-500 mt-2">
                Payments within the selected date range
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500">Overall Outstanding Balance</p>
            <p class="text-2xl font-bold {{ $outstandingBalance > 0 ? 'text-red-600' : 'text-green-700' }} mt-2">
                ₦{{ number_format(max(0, (float) $outstandingBalance), 2) }}
            </p>
            <p class="text-xs text-gray-500 mt-2">
                All purchases minus all payments recorded
            </p>
        </div>

    </div>

    {{-- Purchases Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Purchase Details</h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $purchases->count() }} purchase record(s)
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Item</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Quantity</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Unit Price</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Total</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td class="px-5 py-3 text-sm text-gray-600 whitespace-nowrap">
                                {{ $purchase->purchase_date?->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-800">
                                {{ $purchase->customer?->name ?? 'Deleted Customer' }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">
                                {{ $purchase->item_name }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600 text-right">
                                {{ number_format($purchase->quantity) }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600 text-right whitespace-nowrap">
                                ₦{{ number_format((float) $purchase->unit_price, 2) }}
                            </td>
                            <td class="px-5 py-3 text-sm font-semibold text-gray-800 text-right whitespace-nowrap">
                                ₦{{ number_format((float) $purchase->total_amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">
                                No purchases found for this date range.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Payment Details</h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $payments->count() }} payment record(s)
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Payment Date</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Purchase</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Method</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Reference</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Amount</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="px-5 py-3 text-sm text-gray-600 whitespace-nowrap">
                                {{ $payment->payment_date?->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-800">
                                {{ $payment->purchase?->customer?->name ?? 'Unknown Customer' }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">
                                {{ $payment->purchase?->item_name ?? 'Deleted Purchase' }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">
                                {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">
                                {{ $payment->reference ?: '—' }}
                            </td>
                            <td class="px-5 py-3 text-sm font-semibold text-gray-800 text-right whitespace-nowrap">
                                ₦{{ number_format((float) $payment->amount_paid, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">
                                No payments found for this date range.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-xs text-gray-500 text-right">
        Report generated on {{ now()->format('d M Y, h:i A') }}
    </p>

</div>

{{-- Print Styling --}}
<style>
    @media print {
        body {
            background: white !important;
        }

        nav,
        aside,
        header,
        footer,
        .print\:hidden {
            display: none !important;
        }

        #report-content {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .bg-white {
            box-shadow: none !important;
        }

        table {
            width: 100% !important;
            font-size: 10px !important;
        }

        th, td {
            padding: 6px !important;
        }

        tr {
            break-inside: avoid;
        }
    }
</style>
@endsection