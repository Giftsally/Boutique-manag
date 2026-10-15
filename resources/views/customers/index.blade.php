
@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Customers
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Manage your boutique customer records.
            </p>
        </div>

        <button
            type="button"
            onclick="openCustomerModal('create')"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700"
        >
            <span class="text-lg leading-none">+</span>
            Add Customer
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">All Customers</h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $customers->total() }} customer records
                </p>
            </div>

            <form method="GET" action="{{ route('customers.index') }}" class="flex w-full gap-2 sm:max-w-md">
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search name, phone or email..."
                    class="min-w-0 flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

                <button
                    type="submit"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    Search
                </button>

                @if (request('search'))
                    <a
                        href="{{ route('customers.index') }}"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-100"
                    >
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Customer
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Phone
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Email
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Date Added
                        </th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($customers as $customer)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-700">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div class="font-medium text-slate-900">
                                        {{ $customer->name }}
                                    </div>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $customer->phone }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $customer->email ?: '—' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                {{ $customer->created_at->format('d M Y') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3 text-sm">
                                    <button
                                        type="button"
                                        data-customer-view
                                        data-name="{{ $customer->name }}"
                                        data-phone="{{ $customer->phone }}"
                                        data-email="{{ $customer->email }}"
                                        data-address="{{ $customer->address }}"
                                        data-date="{{ $customer->created_at->format('d M Y') }}"
                                        onclick="openCustomerModal('view', this)"
                                        class="font-medium text-slate-600 hover:text-slate-950"
                                    >
                                        View
                                    </button>

                                    <button
                                        type="button"
                                        data-customer-edit
                                        data-id="{{ $customer->id }}"
                                        data-name="{{ $customer->name }}"
                                        data-phone="{{ $customer->phone }}"
                                        data-email="{{ $customer->email }}"
                                        data-address="{{ $customer->address }}"
                                        onclick="openCustomerModal('edit', this)"
                                        class="font-medium text-blue-700 hover:text-blue-900"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        data-customer-delete
                                        data-id="{{ $customer->id }}"
                                        data-name="{{ $customer->name }}"
                                        onclick="deleteCustomer(this)"
                                        class="font-medium text-red-600 hover:text-red-800"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <h3 class="font-semibold text-slate-800">
                                    No customers found
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Add a customer to start building your records.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Showing {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }}
                of {{ $customers->total() }} customers
            </p>

            <div>
                {{ $customers->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<div
    id="customerModal"
    class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-950/50 p-4"
    onclick="if (event.target === this) closeCustomerModal()"
>
    <div class="my-auto w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-2xl">

        <div class="flex items-start justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h2 id="customerModalTitle" class="text-lg font-semibold text-slate-900">
                    Add Customer
                </h2>
                <p id="customerModalDescription" class="mt-1 text-sm text-slate-500">
                    Enter the customer's information below.
                </p>
            </div>

            <button
                type="button"
                onclick="closeCustomerModal()"
                class="rounded-lg px-2 py-1 text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                aria-label="Close modal"
            >
                &times;
            </button>
        </div>

        <form id="customerForm" class="space-y-5 p-6">
            @csrf

            <div id="customerFormFields" class="space-y-5">
                <div>
                    <label for="customerName" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="customerName"
                        name="name"
                        type="text"
                        required
                        maxlength="255"
                        placeholder="Enter customer's full name"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >
                    <p class="mt-1 hidden text-sm text-red-600" data-error="name"></p>
                </div>

                <div>
                    <label for="customerPhone" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="customerPhone"
                        name="phone"
                        type="tel"
                        required
                        maxlength="20"
                        placeholder="Enter phone number"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >
                    <p class="mt-1 hidden text-sm text-red-600" data-error="phone"></p>
                </div>

                <div>
                    <label for="customerEmail" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Email Address
                    </label>
                    <input
                        id="customerEmail"
                        name="email"
                        type="email"
                        maxlength="255"
                        placeholder="customer@example.com"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    >
                    <p class="mt-1 hidden text-sm text-red-600" data-error="email"></p>
                </div>

                <div>
                    <label for="customerAddress" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Address
                    </label>
                    <textarea
                        id="customerAddress"
                        name="address"
                        rows="3"
                        maxlength="2000"
                        placeholder="Enter customer's address"
                        class="w-full resize-y rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    ></textarea>
                    <p class="mt-1 hidden text-sm text-red-600" data-error="address"></p>
                </div>
            </div>

            <div id="customerViewDetails" class="hidden space-y-4">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Full Name</p>
                    <p id="viewCustomerName" class="mt-1 text-sm font-medium text-slate-900"></p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Phone Number</p>
                    <p id="viewCustomerPhone" class="mt-1 text-sm text-slate-900"></p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Email Address</p>
                    <p id="viewCustomerEmail" class="mt-1 text-sm text-slate-900"></p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Address</p>
                    <p id="viewCustomerAddress" class="mt-1 whitespace-pre-wrap text-sm text-slate-900"></p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Date Added</p>
                    <p id="viewCustomerDate" class="mt-1 text-sm text-slate-900"></p>
                </div>
            </div>

            <div id="customerFormMessage" class="hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    onclick="closeCustomerModal()"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    Close
                </button>

                <button
                    id="customerSubmitButton"
                    type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
                >
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</div>

<div
    id="customerToast"
    class="fixed right-4 top-4 z-[60] hidden max-w-sm rounded-lg border border-slate-200 bg-white px-5 py-4 text-sm font-medium text-slate-800 shadow-lg"
    role="status"
    aria-live="polite"
></div>

<script>
    const customerModal = document.getElementById('customerModal');
    const customerForm = document.getElementById('customerForm');
    const customerFormFields = document.getElementById('customerFormFields');
    const customerViewDetails = document.getElementById('customerViewDetails');
    const customerFormMessage = document.getElementById('customerFormMessage');
    const customerSubmitButton = document.getElementById('customerSubmitButton');

    let customerModalMode = 'create';
    let editingCustomerId = null;

    function openCustomerModal(mode, button = null) {
        customerModalMode = mode;
        editingCustomerId = button?.dataset.id ?? null;

        customerForm.reset();
        clearCustomerErrors();
        customerFormMessage.classList.add('hidden');

        customerFormFields.classList.toggle('hidden', mode === 'view');
        customerViewDetails.classList.toggle('hidden', mode !== 'view');
        customerSubmitButton.classList.toggle('hidden', mode === 'view');

        if (mode === 'create') {
            document.getElementById('customerModalTitle').textContent = 'Add Customer';
            document.getElementById('customerModalDescription').textContent =
                "Enter the customer's information below.";
            customerSubmitButton.textContent = 'Save Customer';
        }

        if (mode === 'edit') {
            document.getElementById('customerModalTitle').textContent = 'Edit Customer';
            document.getElementById('customerModalDescription').textContent =
                'Update the customer information below.';
            customerSubmitButton.textContent = 'Save Changes';

            customerForm.elements.name.value = button.dataset.name || '';
            customerForm.elements.phone.value = button.dataset.phone || '';
            customerForm.elements.email.value = button.dataset.email || '';
            customerForm.elements.address.value = button.dataset.address || '';
        }

        if (mode === 'view') {
            document.getElementById('customerModalTitle').textContent = 'Customer Details';
            document.getElementById('customerModalDescription').textContent =
                'Review the saved customer information.';

            document.getElementById('viewCustomerName').textContent = button.dataset.name || '—';
            document.getElementById('viewCustomerPhone').textContent = button.dataset.phone || '—';
            document.getElementById('viewCustomerEmail').textContent = button.dataset.email || '—';
            document.getElementById('viewCustomerAddress').textContent = button.dataset.address || '—';
            document.getElementById('viewCustomerDate').textContent = button.dataset.date || '—';
        }

        customerModal.classList.remove('hidden');
        customerModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        if (mode !== 'view') {
            setTimeout(() => customerForm.elements.name.focus(), 50);
        }
    }

    function closeCustomerModal() {
        customerModal.classList.add('hidden');
        customerModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function clearCustomerErrors() {
        document.querySelectorAll('[data-error]').forEach((element) => {
            element.textContent = '';
            element.classList.add('hidden');
        });

        customerForm.querySelectorAll('[name]').forEach((input) => {
            input.classList.remove('border-red-500');
        });
    }

    function showCustomerMessage(message, type = 'error') {
        customerFormMessage.textContent = message;
        customerFormMessage.className = 'rounded-lg px-4 py-3 text-sm';

        customerFormMessage.classList.add(
            ...(type === 'success'
                ? ['border', 'border-green-200', 'bg-green-50', 'text-green-800']
                : ['border', 'border-red-200', 'bg-red-50', 'text-red-700'])
        );
    }

    function showCustomerToast(message) {
        const toast = document.getElementById('customerToast');
        toast.textContent = message;
        toast.classList.remove('hidden');

        setTimeout(() => toast.classList.add('hidden'), 3500);
    }

    customerForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (customerModalMode === 'view') {
            return;
        }

        clearCustomerErrors();
        customerFormMessage.classList.add('hidden');

        const originalText = customerSubmitButton.textContent;
        customerSubmitButton.disabled = true;
        customerSubmitButton.textContent = 'Saving...';

        const url = customerModalMode === 'edit'
            ? `/customers/${editingCustomerId}`
            : '{{ route('customers.store') }}';

        const formData = new FormData(customerForm);

        if (customerModalMode === 'edit') {
            formData.append('_method', 'PUT');
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': customerForm.querySelector('[name="_token"]').value,
                },
                body: formData,
            });

            const result = await response.json();

            if (response.status === 422) {
                Object.entries(result.errors || {}).forEach(([field, messages]) => {
                    const errorElement = document.querySelector(`[data-error="${field}"]`);
                    const input = customerForm.elements[field];

                    if (errorElement) {
                        errorElement.textContent = messages[0];
                        errorElement.classList.remove('hidden');
                    }

                    if (input) {
                        input.classList.add('border-red-500');
                    }
                });

                showCustomerMessage('Please correct the highlighted fields.');
                return;
            }

            if (!response.ok) {
                throw new Error(result.message || 'Unable to save the customer.');
            }

            closeCustomerModal();
            showCustomerToast(result.message || 'Customer saved successfully.');

            window.location.reload();
        } catch (error) {
            showCustomerMessage(error.message || 'Something went wrong. Please try again.');
        } finally {
            customerSubmitButton.disabled = false;
            customerSubmitButton.textContent = originalText;
        }
    });

    async function deleteCustomer(button) {
        const customerId = button.dataset.id;
        const customerName = button.dataset.name;

        if (!confirm(`Are you sure you want to delete ${customerName}? This action cannot be undone.`)) {
            return;
        }

        try {
            const response = await fetch(`/customers/${customerId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': customerForm.querySelector('[name="_token"]').value,
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ _method: 'DELETE' }),
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Unable to delete this customer.');
            }

            showCustomerToast(result.message || 'Customer deleted successfully.');
            window.location.reload();
        } catch (error) {
            alert(error.message || 'Something went wrong while deleting the customer.');
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !customerModal.classList.contains('hidden')) {
            closeCustomerModal();
        }
    });
</script>
@endsection
