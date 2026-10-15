@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
@php
    $paymentMethods = [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'pos' => 'POS',
        'other' => 'Other',
    ];
@endphp

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Payments
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Record customer payments and track outstanding balances.
            </p>
        </div>

        <button
            type="button"
            onclick="openPaymentModal('create')"
            class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
        >
            Record Payment
        </button>
    </div>

    {{-- Search --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form
            method="GET"
            action="{{ route('payments.index') }}"
            class="flex flex-col gap-3 sm:flex-row"
        >
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search customer, item or reference..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
            >

            <div class="flex gap-2">
                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-700"
                >
                    Search
                </button>

                @if(request('search'))
                    <a
                        href="{{ route('payments.index') }}"
                        class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Payment Records --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Payment Records</h2>

            <span class="text-sm text-slate-500">
                {{ $payments->total() }} records
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Customer / Item
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Purchase Total
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Amount Paid
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Payment Date
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Method
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Reference
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="font-medium text-slate-900">
                                    {{ $payment->purchase?->customer?->name ?? 'Customer unavailable' }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $payment->purchase?->item_name ?? 'Purchase unavailable' }}
                                </div>

                                <div class="mt-1 text-xs text-slate-400">
                                    Payment #{{ $payment->id }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                ₦{{ number_format((float) ($payment->purchase?->total_amount ?? 0), 2) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-900">
                                ₦{{ number_format((float) $payment->amount_paid, 2) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $payment->payment_date
                                    ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y')
                                    : '—' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                    {{ $paymentMethods[$payment->payment_method] ?? ucfirst($payment->payment_method ?? 'Other') }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $payment->reference ?: '—' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <div class="flex justify-end gap-3 text-sm">
                                    <button
                                        type="button"
                                        onclick="openPaymentModal('view', {{ Illuminate\Support\Js::from($payment) }})"
                                        class="font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        View
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openPaymentModal('edit', {{ Illuminate\Support\Js::from($payment) }})"
                                        class="font-medium text-indigo-600 hover:text-indigo-800"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        onclick="deletePayment({{ $payment->id }})"
                                        class="font-medium text-red-600 hover:text-red-800"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <p class="font-medium text-slate-700">
                                    No payments found
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Record a payment or adjust your search.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Record / Edit / View Payment Modal --}}
<div
    id="paymentModal"
    class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-950/50 p-4"
>
    <div class="my-auto w-full max-w-2xl rounded-2xl bg-white shadow-2xl">

        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 id="paymentModalTitle" class="text-lg font-bold text-slate-900">
                    Record Payment
                </h2>

                <p id="paymentModalSubtitle" class="mt-1 text-sm text-slate-500">
                    Enter the payment details below.
                </p>
            </div>

            <button
                type="button"
                onclick="closePaymentModal()"
                class="rounded-lg p-2 text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                aria-label="Close modal"
            >
                &times;
            </button>
        </div>

        <form id="paymentForm" class="space-y-5 px-6 py-5">
            <input type="hidden" id="paymentId">

            <div
                id="paymentFormErrors"
                class="hidden rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"
                role="alert"
            ></div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- Purchase --}}
                <div class="sm:col-span-2">
                    <label for="purchase_id" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Purchase <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="purchase_id"
                        name="purchase_id"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                        <option value="">Select a purchase</option>

                        @foreach($purchases as $purchase)
                            <option
                                value="{{ $purchase->id }}"
                                data-total="{{ $purchase->total_amount }}"
                                data-paid="{{ $purchase->payments_sum_amount_paid ?? 0 }}"
                            >
                                {{ $purchase->customer?->name ?? 'Unknown customer' }}
                                — {{ $purchase->item_name }}
                                — ₦{{ number_format((float) $purchase->total_amount, 2) }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-1.5 text-xs text-slate-500">
                        Select the purchase this payment belongs to.
                    </p>
                </div>

                {{-- Purchase Summary --}}
                <div class="sm:col-span-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <p class="text-xs font-medium text-slate-500">Purchase Total</p>
                        <p id="purchaseTotalDisplay" class="mt-1 text-base font-bold text-slate-900">
                            ₦0.00
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <p class="text-xs font-medium text-slate-500">Previously Paid</p>
                        <p id="previouslyPaidDisplay" class="mt-1 text-base font-bold text-slate-900">
                            ₦0.00
                        </p>
                    </div>

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                        <p class="text-xs font-medium text-amber-800">Outstanding Balance</p>
                        <p id="outstandingDisplay" class="mt-1 text-base font-bold text-amber-900">
                            ₦0.00
                        </p>
                    </div>
                </div>

                {{-- Amount --}}
                <div>
                    <label for="amount_paid" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Amount Paid (₦) <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        id="amount_paid"
                        name="amount_paid"
                        min="0.01"
                        step="0.01"
                        required
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                {{-- Payment Date --}}
                <div>
                    <label for="payment_date" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Payment Date <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        id="payment_date"
                        name="payment_date"
                        required
                        value="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                {{-- Payment Method --}}
                <div>
                    <label for="payment_method" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Payment Method <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="payment_method"
                        name="payment_method"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                        <option value="">Select method</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="pos">POS</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                {{-- Reference --}}
                <div>
                    <label for="reference" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Reference
                    </label>

                    <input
                        type="text"
                        id="reference"
                        name="reference"
                        maxlength="255"
                        placeholder="Transaction reference (optional)"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                {{-- Notes --}}
                <div class="sm:col-span-2">
                    <label for="payment_notes" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Notes
                    </label>

                    <textarea
                        id="payment_notes"
                        name="notes"
                        rows="3"
                        maxlength="2000"
                        placeholder="Optional payment details..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    ></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button
                    type="button"
                    onclick="closePaymentModal()"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    id="paymentSubmitButton"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700"
                >
                    Save Payment
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast --}}
<div
    id="paymentToast"
    class="fixed bottom-5 right-5 z-[60] hidden max-w-sm rounded-lg px-5 py-3 text-sm font-medium text-white shadow-lg"
    role="status"
    aria-live="polite"
></div>

<script>
    const paymentCsrfToken = @json(csrf_token());
    const paymentDefaultDate = @json(now()->format('Y-m-d'));

    let currentPaymentMode = 'create';
    let paymentToastTimer;

    function formatPaymentCurrency(value) {
        return '₦' + Number(value || 0).toLocaleString('en-NG', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function getSelectedPurchase() {
        const select = document.getElementById('purchase_id');
        const option = select.options[select.selectedIndex];

        if (!option || !option.value) {
            return null;
        }

        return {
            id: Number(option.value),
            total: Number(option.dataset.total) || 0,
            paid: Number(option.dataset.paid) || 0
        };
    }

    function getPaymentBalance(purchase) {
        if (!purchase) {
            return {
                total: 0,
                paid: 0,
                outstanding: 0
            };
        }

        let paid = purchase.paid;

        const paymentId = document.getElementById('paymentId').value;
        const selectedPayment = window.currentEditingPayment;

        // When editing a payment against the same purchase,
        // exclude its current amount from the existing payments.
        if (
            currentPaymentMode === 'edit' &&
            selectedPayment &&
            Number(paymentId) === Number(selectedPayment.id) &&
            Number(selectedPayment.purchase_id) === purchase.id
        ) {
            paid -= Number(selectedPayment.amount_paid) || 0;
        }

        paid = Math.max(0, paid);

        return {
            total: purchase.total,
            paid: paid,
            outstanding: Math.max(0, purchase.total - paid)
        };
    }

    function updatePaymentBalance() {
        const purchase = getSelectedPurchase();
        const balance = getPaymentBalance(purchase);

        document.getElementById('purchaseTotalDisplay').textContent =
            formatPaymentCurrency(balance.total);

        document.getElementById('previouslyPaidDisplay').textContent =
            formatPaymentCurrency(balance.paid);

        document.getElementById('outstandingDisplay').textContent =
            formatPaymentCurrency(balance.outstanding);

        const amountInput = document.getElementById('amount_paid');

        amountInput.max = balance.outstanding > 0
            ? balance.outstanding.toFixed(2)
            : '';

        if (purchase && balance.outstanding <= 0) {
            document.getElementById('outstandingDisplay').classList.add('text-red-700');
            document.getElementById('outstandingDisplay').classList.remove('text-amber-900');
        } else {
            document.getElementById('outstandingDisplay').classList.remove('text-red-700');
            document.getElementById('outstandingDisplay').classList.add('text-amber-900');
        }
    }

    function openPaymentModal(mode, payment = null) {
        currentPaymentMode = mode;
        window.currentEditingPayment = mode === 'edit' ? payment : null;

        const modal = document.getElementById('paymentModal');
        const form = document.getElementById('paymentForm');
        const title = document.getElementById('paymentModalTitle');
        const subtitle = document.getElementById('paymentModalSubtitle');
        const submitButton = document.getElementById('paymentSubmitButton');
        const errors = document.getElementById('paymentFormErrors');

        form.reset();

        errors.classList.add('hidden');
        errors.textContent = '';

        document.getElementById('paymentId').value = '';
        document.getElementById('payment_date').value = paymentDefaultDate;

        if (mode === 'create') {
            title.textContent = 'Record Payment';
            subtitle.textContent = 'Enter the payment details below.';
            submitButton.textContent = 'Save Payment';
            document.getElementById('purchase_id').value = '';
            document.getElementById('amount_paid').value = '';
        } else {
            document.getElementById('paymentId').value = payment.id;
            document.getElementById('purchase_id').value = payment.purchase_id;
            document.getElementById('amount_paid').value = payment.amount_paid;
            document.getElementById('payment_date').value =
                String(payment.payment_date).substring(0, 10);
            document.getElementById('payment_method').value = payment.payment_method;
            document.getElementById('reference').value = payment.reference ?? '';
            document.getElementById('payment_notes').value = payment.notes ?? '';

            title.textContent = mode === 'edit' ? 'Edit Payment' : 'Payment Details';

            subtitle.textContent = mode === 'edit'
                ? 'Update the payment information.'
                : 'Review the saved payment information.';

            submitButton.textContent = 'Update Payment';
        }

        updatePaymentBalance();

        const fields = form.querySelectorAll('input, select, textarea');

        fields.forEach(field => {
            if (field.id !== 'paymentId') {
                field.disabled = mode === 'view';
            }
        });

        submitButton.classList.toggle('hidden', mode === 'view');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        window.currentEditingPayment = null;
    }

    document.getElementById('purchase_id')
        .addEventListener('change', updatePaymentBalance);

    document.getElementById('paymentForm')
        .addEventListener('submit', async function (event) {
            event.preventDefault();

            if (currentPaymentMode === 'view') {
                return;
            }

            const submitButton = document.getElementById('paymentSubmitButton');
            const errors = document.getElementById('paymentFormErrors');
            const paymentId = document.getElementById('paymentId').value;

            errors.classList.add('hidden');
            errors.textContent = '';

            const payload = {
                purchase_id: document.getElementById('purchase_id').value,
                amount_paid: document.getElementById('amount_paid').value,
                payment_date: document.getElementById('payment_date').value,
                payment_method: document.getElementById('payment_method').value,
                reference: document.getElementById('reference').value.trim(),
                notes: document.getElementById('payment_notes').value.trim()
            };

            if (!payload.purchase_id) {
                errors.textContent = 'Please select a purchase.';
                errors.classList.remove('hidden');
                return;
            }

            const url = currentPaymentMode === 'edit'
                ? `/payments/${paymentId}`
                : @json(route('payments.store'));

            const method = currentPaymentMode === 'edit' ? 'PUT' : 'POST';

            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': paymentCsrfToken
                    },
                    body: JSON.stringify({
                        ...payload,
                        _method: method
                    })
                });

                const result = await response.json();

                if (!response.ok) {
                    if (response.status === 422 && result.errors) {
                        errors.innerHTML = Object.values(result.errors)
                            .flat()
                            .map(message =>
                                `<p>${escapePaymentHtml(message)}</p>`
                            )
                            .join('');
                    } else {
                        errors.textContent =
                            result.message || 'Unable to save the payment.';
                    }

                    errors.classList.remove('hidden');
                    return;
                }

                closePaymentModal();

                showPaymentToast(
                    result.message || 'Payment saved successfully.',
                    'success'
                );

                setTimeout(() => window.location.reload(), 700);

            } catch (error) {
                errors.textContent =
                    'A network error occurred. Please try again.';

                errors.classList.remove('hidden');

            } finally {
                submitButton.disabled = false;

                submitButton.textContent = currentPaymentMode === 'edit'
                    ? 'Update Payment'
                    : 'Save Payment';
            }
        });

    async function deletePayment(paymentId) {
        const confirmed = confirm(
            'Are you sure you want to delete this payment? ' +
            'Deleting it will increase the purchase outstanding balance.'
        );

        if (!confirmed) {
            return;
        }

        try {
            const response = await fetch(`/payments/${paymentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': paymentCsrfToken
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            });

            const result = await response.json();

            if (!response.ok) {
                showPaymentToast(
                    result.message || 'Unable to delete the payment.',
                    'error'
                );

                return;
            }

            showPaymentToast(
                result.message || 'Payment deleted successfully.',
                'success'
            );

            setTimeout(() => window.location.reload(), 700);

        } catch (error) {
            showPaymentToast(
                'A network error occurred. Please try again.',
                'error'
            );
        }
    }

    function showPaymentToast(message, type = 'success') {
        const toast = document.getElementById('paymentToast');

        clearTimeout(paymentToastTimer);

        toast.textContent = message;

        toast.className =
            'fixed bottom-5 right-5 z-[60] max-w-sm rounded-lg px-5 py-3 text-sm font-medium text-white shadow-lg';

        toast.classList.add(
            type === 'success' ? 'bg-emerald-700' : 'bg-red-700'
        );

        paymentToastTimer = setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }

    function escapePaymentHtml(value) {
        return String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[character]);
    }

    document.getElementById('paymentModal')
        .addEventListener('click', function (event) {
            if (event.target === this) {
                closePaymentModal();
            }
        });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePaymentModal();
        }
    });
</script>
@endsection