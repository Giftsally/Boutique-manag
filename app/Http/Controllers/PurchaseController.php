<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $purchases = Purchase::query()
            ->with('customer')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('item_name', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('purchase_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

            
        public function store(Request $request)
        {
            $validated = $request->validate([
                'customer_id' => ['required', 'exists:customers,id'],
                'item_name' => ['required', 'string', 'max:255'],
                'quantity' => ['required', 'integer', 'min:1'],
                'unit_price' => ['required', 'numeric', 'min:0'],
                'purchase_date' => ['required', 'date'],
                'notes' => ['nullable', 'string', 'max:2000'],
            ]);

            $validated['total_amount'] =
                $validated['quantity'] * $validated['unit_price'];

            Purchase::create($validated);

            return response()->json([
                'message' => 'Purchase added successfully.',
            ], 201);
        }


        public function update(Request $request, Purchase $purchase)
        {
            $validated = $request->validate([
                'customer_id' => ['required', 'exists:customers,id'],
                'item_name' => ['required', 'string', 'max:255'],
                'quantity' => ['required', 'integer', 'min:1'],
                'unit_price' => ['required', 'numeric', 'min:0'],
                'purchase_date' => ['required', 'date'],
                'notes' => ['nullable', 'string', 'max:2000'],
            ]);

            $validated['total_amount'] =
                $validated['quantity'] * $validated['unit_price'];

            $purchase->update($validated);

            return response()->json([
                'message' => 'Purchase updated successfully.',
            ]);
        }


        public function destroy(Purchase $purchase)
        {
            $purchase->delete();

            return response()->json([
                'message' => 'Purchase deleted successfully.',
            ]);
        }

}