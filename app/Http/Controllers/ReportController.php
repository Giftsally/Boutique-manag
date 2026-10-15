<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display purchase and payment reports.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;

        $purchasesQuery = Purchase::query()
            ->with('customer');

        $paymentsQuery = Payment::query()
            ->with(['purchase.customer']);

        if ($startDate) {
            $purchasesQuery->whereDate('purchase_date', '>=', $startDate);
            $paymentsQuery->whereDate('payment_date', '>=', $startDate);
        }

        if ($endDate) {
            $purchasesQuery->whereDate('purchase_date', '<=', $endDate);
            $paymentsQuery->whereDate('payment_date', '<=', $endDate);
        }

        $purchases = $purchasesQuery
            ->latest('purchase_date')
            ->get();

        $payments = $paymentsQuery
            ->latest('payment_date')
            ->get();

        $totalPurchaseValue = (clone $purchasesQuery)->sum('total_amount');

        $totalPaymentsReceived = (clone $paymentsQuery)->sum('amount_paid');

        $outstandingBalance = (float) Purchase::sum('total_amount')
            - (float) Payment::sum('amount_paid');

        return view('reports.index', compact(
            'purchases',
            'payments',
            'startDate',
            'endDate',
            'totalPurchaseValue',
            'totalPaymentsReceived',
            'outstandingBalance'
        ));
    }
}