<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Notifications\PurchaseOrderCreatedNotification;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('quotation.creator', 'creator', 'approver')
            ->latest()
            ->paginate(10);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(Request $request)
    {
        $quotationId = $request->get('quotation_id');
        $quotation = Quotation::with('quotationItems')->findOrFail($quotationId);

        // Check if PO already exists
        if ($quotation->purchaseOrder) {
            return redirect()->back()->with('error', 'Purchase Order sudah dibuat untuk quotation ini.');
        }

        return view('purchase-orders.create', compact('quotation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
            'po_number' => 'required|string|unique:purchase_orders',
            'po_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $quotation = Quotation::with('quotationItems')->findOrFail($validated['quotation_id']);

        // Check if PO already exists
        if ($quotation->purchaseOrder) {
            return redirect()->back()->with('error', 'Purchase Order sudah dibuat untuk quotation ini.');
        }

        // Calculate total amount
        $totalAmount = $quotation->quotationItems->sum(function ($item) {
            return $item->harga * (1 - ($item->diskon / 100));
        });

        $purchaseOrder = PurchaseOrder::create([
            'quotation_id' => $validated['quotation_id'],
            'po_number' => $validated['po_number'],
            'po_date' => $validated['po_date'],
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'],
            'created_by' => auth()->id(),
            'status' => 'draft',
        ]);

        // Send notifications to relevant users
        $this->sendPurchaseOrderNotifications($purchaseOrder);

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('quotation.quotationItems', 'creator', 'approver');
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->back()->with('error', 'Purchase Order tidak dapat diapprove.');
        }

        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Purchase Order berhasil diapprove.');
    }

    private function sendPurchaseOrderNotifications(PurchaseOrder $purchaseOrder)
    {
        // Notify sales person
        $salesPerson = $purchaseOrder->quotation->creator;
        $salesPerson->notify(new PurchaseOrderCreatedNotification($purchaseOrder, 'sales'));

        // Notify inputer sap users
        $inputerSapUsers = \App\Models\User::role('inputer_sap')->where('status', 'approved')->get();
        foreach ($inputerSapUsers as $user) {
            $user->notify(new PurchaseOrderCreatedNotification($purchaseOrder, 'inputer_sap'));
        }

        // Notify admin users
        $adminUsers = \App\Models\User::role('admin')->where('status', 'approved')->get();
        foreach ($adminUsers as $user) {
            $user->notify(new PurchaseOrderCreatedNotification($purchaseOrder, 'admin'));
        }
    }
}
