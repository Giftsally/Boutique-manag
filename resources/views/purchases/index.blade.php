@extends('layouts.admin')

@section('title', 'Purchases')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Purchases
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Manage customer purchases and track purchase records.
            </p>
        </div>

        <button
            type="button"
            onclick="openPurchaseModal('create')"
            class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
        >
            Add Purchase
        </button>
    </div>

    {{-- Search --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('purchases.index') }}"
              class="flex flex-col gap-3 sm:flex-row">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search item, customer or phone..."
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
                        href="{{ route('purchases.index') }}"
                        class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Purchases Table --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Purchase Records</h2>
            <span class="text-sm text-slate-500">
                {{ $purchases->total() }} records
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Item
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Customer
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Quantity
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Unit Price
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Date
                        </th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="font-medium text-slate-900">
                                    {{ $purchase->item_name }}
                                </div>
                                @if($purchase->notes)
                                    <div class="mt-1 max-w-xs truncate text-xs text-slate-500">
                                        {{ $purchase->notes }}
                                    </div>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $purchase->customer->name }}
                                </div>
                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $purchase->customer->phone }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ number_format($purchase->quantity) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ number_format((float) $purchase->unit_price, 2) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-900">
                                {{ number_format((float) $purchase->total_amount, 2) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $purchase->purchase_date->format('d M Y') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <div class="flex justify-end gap-3 text-sm">
                                    <button
                                        type="button"
                                        onclick="openPurchaseModal('view', {{ Illuminate\Support\Js::from($purchase) }})"
                                        class="font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        View
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openPurchaseModal('edit', {{ Illuminate\Support\Js::from($purchase) }})"
                                        class="font-medium text-indigo-600 hover:text-indigo-800"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        onclick="deletePurchase({{ $purchase->id }})"
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
                                    No purchases found
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    Add a purchase or adjust your search.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Add / Edit / View Modal --}}
<div
    id="purchaseModal"
    class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-950/50 p-4"
>
    <div class="my-auto w-full max-w-2xl rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 id="purchaseModalTitle" class="text-lg font-bold text-slate-900">
                    Add Purchase
                </h2>
                <p id="purchaseModalSubtitle" class="mt-1 text-sm text-slate-500">
                    Enter the purchase details below.
                </p>
            </div>

            <button
                type="button"
                onclick="closePurchaseModal()"
                class="rounded-lg p-2 text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                aria-label="Close modal"
            >
                &times;
            </button>
        </div>

        <form id="purchaseForm" class="space-y-5 px-6 py-5">
            <input type="hidden" id="purchaseId">

            <div id="purchaseFormErrors"
                 class="hidden rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"></div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div class="sm:col-span-2">
                    <label for="customer_id" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Customer <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="customer_id"
                        name="customer_id"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                        <option value="">Select a customer</option>
                        @foreach(\App\Models\Customer::orderBy('name')->get() as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }} — {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="item_name" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Item Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="item_name"
                        name="item_name"
                        required
                        maxlength="255"
                        placeholder="e.g. Senator material, gown, shirt"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label for="quantity" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Quantity <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        min="1"
                        step="1"
                        required
                        value="1"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label for="unit_price" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Unit Price <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="unit_price"
                        name="unit_price"
                        min="0"
                        step="0.01"
                        required
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div>
                    <label for="total_amount" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Total Amount
                    </label>
                    <input
                        type="text"
                        id="total_amount"
                        readonly
                        value="0.00"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700"
                    >
                </div>

                <div>
                    <label for="purchase_date" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Purchase Date <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="purchase_date"
                        name="purchase_date"
                        required
                        value="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Notes
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        maxlength="2000"
                        placeholder="Optional purchase details..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    ></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button
                    type="button"
                    onclick="closePurchaseModal()"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    id="purchaseSubmitButton"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700"
                >
                    Save Purchase
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Toast --}}
<div
    id="purchaseToast"
    class="fixed bottom-5 right-5 z-[60] hidden max-w-sm rounded-lg px-5 py-3 text-sm font-medium text-white shadow-lg"
    role="status"
    aria-live="polite"
></div>

<script>
    const purchaseCsrfToken = @json(csrf_token());

    let currentPurchaseMode = 'create';
    let purchaseToastTimer;

    function openPurchaseModal(mode, purchase = null) {
        currentPurchaseMode = mode;

        const modal = document.getElementById('purchaseModal');
        const form = document.getElementById('purchaseForm');
        const title = document.getElementById('purchaseModalTitle');
        const subtitle = document.getElementById('purchaseModalSubtitle');
        const submitButton = document.getElementById('purchaseSubmitButton');
        const errors = document.getElementById('purchaseFormErrors');

        form.reset();
        errors.classList.add('hidden');
        errors.innerHTML = '';
        document.getElementById('purchaseId').value = '';
        document.getElementById('quantity').value = 1;
        document.getElementById('purchase_date').value =
            @json(now()->format('Y-m-d'));

        if (mode === 'create') {
            title.textContent = 'Add Purchase';
            subtitle.textContent = 'Enter the purchase details below.';
            submitButton.textContent = 'Save Purchase';
        } else {
            document.getElementById('purchaseId').value = purchase.id;
            document.getElementById('customer_id').value = purchase.customer_id;
            document.getElementById('item_name').value = purchase.item_name;
            document.getElementById('quantity').value = purchase.quantity;
            document.getElementById('unit_price').value = purchase.unit_price;
            document.getElementById('purchase_date').value =
                String(purchase.purchase_date).substring(0, 10);
            document.getElementById('notes').value = purchase.notes ?? '';

            title.textContent = mode === 'edit' ? 'Edit Purchase' : 'Purchase Details';
            subtitle.textContent = mode === 'edit'
                ? 'Update the purchase information.'
                : 'Review the saved purchase information.';
            submitButton.textContent = 'Update Purchase';
        }

        updatePurchaseTotal();

        const fields = form.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            if (field.id !== 'total_amount' && field.id !== 'purchaseId') {
                field.disabled = mode === 'view';
            }
        });

        submitButton.classList.toggle('hidden', mode === 'view');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePurchaseModal() {
        const modal = document.getElementById('purchaseModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function updatePurchaseTotal() {
        const quantity = Number(document.getElementById('quantity').value) || 0;
        const unitPrice = Number(document.getElementById('unit_price').value) || 0;

        document.getElementById('total_amount').value =
            (quantity * unitPrice).toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
    }

    document.getElementById('quantity').addEventListener('input', updatePurchaseTotal);
    document.getElementById('unit_price').addEventListener('input', updatePurchaseTotal);

    document.getElementById('purchaseForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        if (currentPurchaseMode === 'view') {
            return;
        }

        const submitButton = document.getElementById('purchaseSubmitButton');
        const errors = document.getElementById('purchaseFormErrors');
        const purchaseId = document.getElementById('purchaseId').value;

        errors.classList.add('hidden');
        errors.innerHTML = '';

        const payload = {
            customer_id: document.getElementById('customer_id').value,
            item_name: document.getElementById('item_name').value.trim(),
            quantity: document.getElementById('quantity').value,
            unit_price: document.getElementById('unit_price').value,
            purchase_date: document.getElementById('purchase_date').value,
            notes: document.getElementById('notes').value.trim()
        };

        const url = currentPurchaseMode === 'edit'
            ? `/purchases/${purchaseId}`
            : @json(route('purchases.store'));

        const method = currentPurchaseMode === 'edit' ? 'PUT' : 'POST';

        submitButton.disabled = true;
        submitButton.textContent = 'Saving...';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': purchaseCsrfToken
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
                        .map(message => `<p>${escapePurchaseHtml(message)}</p>`)
                        .join('');
                } else {
                    errors.textContent = result.message || 'Unable to save the purchase.';
                }

                errors.classList.remove('hidden');
                return;
            }

            closePurchaseModal();
            showPurchaseToast(result.message || 'Purchase saved successfully.', 'success');

            setTimeout(() => window.location.reload(), 700);
        } catch (error) {
            errors.textContent = 'A network error occurred. Please try again.';
            errors.classList.remove('hidden');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = currentPurchaseMode === 'edit'
                ? 'Update Purchase'
                : 'Save Purchase';
        }
    });

    async function deletePurchase(purchaseId) {
        const confirmed = confirm(
            'Are you sure you want to delete this purchase? This action cannot be undone.'
        );

        if (!confirmed) {
            return;
        }

        try {
            const response = await fetch(`/purchases/${purchaseId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': purchaseCsrfToken
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            });

            const result = await response.json();

            if (!response.ok) {
                showPurchaseToast(result.message || 'Unable to delete the purchase.', 'error');
                return;
            }

            showPurchaseToast(result.message || 'Purchase deleted successfully.', 'success');
            setTimeout(() => window.location.reload(), 700);
        } catch (error) {
            showPurchaseToast('A network error occurred. Please try again.', 'error');
        }
    }

    function showPurchaseToast(message, type = 'success') {
        const toast = document.getElementById('purchaseToast');

        clearTimeout(purchaseToastTimer);

        toast.textContent = message;
        toast.className = 'fixed bottom-5 right-5 z-[60] max-w-sm rounded-lg px-5 py-3 text-sm font-medium text-white shadow-lg';

        toast.classList.add(type === 'success' ? 'bg-emerald-700' : 'bg-red-700');

        purchaseToastTimer = setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }

    function escapePurchaseHtml(value) {
        return String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[character]);
    }

    document.getElementById('purchaseModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closePurchaseModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePurchaseModal();
        }
    });
</script>
@endsection
