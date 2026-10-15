<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display payments with search and pagination.
     */
public function index(Request $request): View
{
    $search = $request->string('search')->trim()->toString();

    $payments = Payment::query()
        ->with([
            'purchase' => function ($query) {
                $query->with('customer')
                    ->withSum('payments', 'amount_paid');
            },
        ])
        ->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('reference', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhereHas('purchase', function ($purchaseQuery) use ($search) {
                        $purchaseQuery->where('item_name', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($customerQuery) use ($search) {
                                $customerQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            });
                    });
            });
        })
        ->latest('payment_date')
        ->latest('id')
        ->paginate(10)
        ->withQueryString();

    // Load purchases and their customers for the payment form.
    $purchases = Purchase::query()
        ->with('customer')
        ->withSum('payments', 'amount_paid')
        ->latest('purchase_date')
        ->get();

    return view('payments.index', compact('payments', 'purchases'));
}

    /**
     * Record a payment against a purchase.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_id' => ['required', 'exists:purchases,id'],
            'amount_paid' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'payment_date' => ['required', 'date'],
            'payment_method' => [
                'required',
                'in:cash,bank_transfer,pos,other',
            ],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $payment = DB::transaction(function () use ($validated) {
            $purchase = Purchase::query()
                ->lockForUpdate()
                ->findOrFail($validated['purchase_id']);

            $totalPaid = $purchase->payments()->sum('amount_paid');
            $outstanding = round(
                (float) $purchase->total_amount - (float) $totalPaid,
                2
            );

            if ((float) $validated['amount_paid'] > $outstanding) {
                throw ValidationException::withMessages([
                    'amount_paid' => [
                        'The payment exceeds the outstanding balance of ' .
                        number_format(max(0, $outstanding), 2) . '.',
                    ],
                ]);
            }

            return $purchase->payments()->create([
                'amount_paid' => $validated['amount_paid'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Payment recorded successfully.',
            'payment' => $payment,
        ], 201);
    }

    /**
     * Update an existing payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'purchase_id' => ['required', 'exists:purchases,id'],
            'amount_paid' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'payment_date' => ['required', 'date'],
            'payment_method' => [
                'required',
                'in:cash,bank_transfer,pos,other',
            ],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($validated, $payment) {
            $purchaseIds = array_unique([
                (int) $payment->purchase_id,
                (int) $validated['purchase_id'],
            ]);

            sort($purchaseIds);

            $purchases = Purchase::query()
                ->whereIn('id', $purchaseIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $newPurchase = $purchases->get((int) $validated['purchase_id']);

            if (!$newPurchase) {
                throw ValidationException::withMessages([
                    'purchase_id' => ['The selected purchase was not found.'],
                ]);
            }

            // Exclude this payment when calculating the new purchase balance.
            $otherPayments = $newPurchase->payments()
                ->where('id', '!=', $payment->id)
                ->sum('amount_paid');

            $outstanding = round(
                (float) $newPurchase->total_amount - (float) $otherPayments,
                2
            );

            if ((float) $validated['amount_paid'] > $outstanding) {
                throw ValidationException::withMessages([
                    'amount_paid' => [
                        'The payment exceeds the outstanding balance of ' .
                        number_format(max(0, $outstanding), 2) . '.',
                    ],
                ]);
            }

            $payment->update([
                'purchase_id' => $newPurchase->id,
                'amount_paid' => $validated['amount_paid'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Payment updated successfully.',
        ]);
    }

    /**
     * Delete a payment record.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted successfully.',
        ]);
    }
}