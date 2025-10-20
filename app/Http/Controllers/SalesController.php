<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationRevision;
use App\Notifications\QuotationUpdatedNotification;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        return view('sales');
    }

    public function create()
    {
        return view('sales.input-penawaran');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales_person' => 'required|string',
            'jenis_penawaran' => 'required|in:Alat baru,Re-kalibrasi,Perbaikan,Sewa Alat',
            'format_layout' => 'required|in:With total,Without total',
            'nama_customer' => 'required|string|max:255',
            'alamat_customer' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.nama_alat' => 'required|string|max:255',
            'items.*.tipe_alat' => 'required|string|max:255',
            'items.*.merk' => 'nullable|string|max:255',
            'items.*.part_number' => 'required|string|max:255',
            'items.*.kategori_harga' => 'required|in:HARGA INAPROC 2025,HARGA RETAIL 2025,NON E-KATALOG (CUSTOM)',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.ppn' => 'required|in:YA,Tidak',
            'items.*.diskon' => 'nullable|string',
            'pembayaran' => 'required|string',
            'pembayaran_other' => 'nullable|string|max:255',
            'stok' => 'required|string',
            'stok_other' => 'nullable|string|max:255',
            'keterangan_tambahan' => 'nullable|string',
        ]);

        // Handle conditional fields
        if ($validated['pembayaran'] !== 'Other:') {
            $validated['pembayaran_other'] = null;
        }
        if ($validated['stok'] !== 'Other:') {
            $validated['stok_other'] = null;
        }

        $validated['created_by'] = auth()->id();

        // Extract items from validated data
        $items = $validated['items'];
        unset($validated['items']);

        $quotation = Quotation::create($validated);

        // Create quotation items
        foreach ($items as $item) {
            $quotation->quotationItems()->create($item);
        }

        // Send notification to inputer sap users
        $inputerSapUsers = \App\Models\User::role('inputer_sap')->where('status', 'approved')->get();
        foreach ($inputerSapUsers as $user) {
            $user->notify(new \App\Notifications\NewQuotationNotification($quotation));
        }

        return redirect()->route('sales.input-penawaran.create')->with('success', 'Quotation submitted successfully!');
    }

    public function quotations()
    {
        $quotations = Quotation::with('quotationItems')->where('created_by', auth()->id())->latest()->get();
        return view('sales.daftar-penawaran', compact('quotations'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        // Ensure user can only update their own quotations
        if ($quotation->created_by !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'sales_person' => 'required|string',
            'jenis_penawaran' => 'required|in:Alat baru,Re-kalibrasi,Perbaikan,Sewa Alat',
            'format_layout' => 'required|in:With total,Without total',
            'nama_customer' => 'required|string|max:255',
            'alamat_customer' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.nama_alat' => 'required|string|max:255',
            'items.*.tipe_alat' => 'required|string|max:255',
            'items.*.merk' => 'nullable|string|max:255',
            'items.*.part_number' => 'required|string|max:255',
            'items.*.kategori_harga' => 'required|in:HARGA INAPROC 2025,HARGA RETAIL 2025,NON E-KATALOG (CUSTOM)',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.ppn' => 'required|in:YA,Tidak',
            'items.*.diskon' => 'nullable|string',
            'pembayaran' => 'required|string',
            'pembayaran_other' => 'nullable|string|max:255',
            'stok' => 'required|string',
            'stok_other' => 'nullable|string|max:255',
            'keterangan_tambahan' => 'nullable|string',
        ]);

        // Handle conditional fields
        if ($validated['pembayaran'] !== 'Other:') {
            $validated['pembayaran_other'] = null;
        }
        if ($validated['stok'] !== 'Other:') {
            $validated['stok_other'] = null;
        }

        // Store old data for revision tracking
        $oldData = $quotation->toArray();
        $oldData['items'] = $quotation->quotationItems->toArray();

        // Update quotation
        $quotation->update($validated);

        // Update quotation items
        $quotation->quotationItems()->delete(); // Remove existing items
        foreach ($validated['items'] as $item) {
            $quotation->quotationItems()->create($item);
        }

        // Create revision log
        $changes = $this->detectChanges($oldData, $quotation->fresh()->load('quotationItems'));
        if (!empty($changes)) {
            QuotationRevision::create([
                'quotation_id' => $quotation->id,
                'user_id' => auth()->id(),
                'old_data' => $oldData,
                'new_data' => $quotation->toArray() + ['items' => $quotation->quotationItems->toArray()],
                'action' => 'updated',
                'notes' => 'Quotation updated by sales',
            ]);

            // Send notification to inputer sap users
            $inputerSapUsers = \App\Models\User::role('inputer_sap')->where('status', 'approved')->get();
            foreach ($inputerSapUsers as $user) {
                $user->notify(new QuotationUpdatedNotification($quotation, $changes));
            }
        }

        return redirect()->route('sales.daftar-penawaran')->with('success', 'Quotation updated successfully!');
    }

    private function detectChanges($oldData, $newQuotation)
    {
        $changes = [];

        $fieldsToCheck = [
            'sales_person', 'jenis_penawaran', 'format_layout', 'nama_customer',
            'alamat_customer', 'pembayaran', 'pembayaran_other', 'stok',
            'stok_other', 'keterangan_tambahan'
        ];

        foreach ($fieldsToCheck as $field) {
            if ($oldData[$field] !== $newQuotation->$field) {
                $changes[] = $field;
            }
        }

        // Check if items changed
        $oldItems = collect($oldData['items']);
        $newItems = $newQuotation->quotationItems;

        if ($oldItems->count() !== $newItems->count()) {
            $changes[] = 'items';
        } else {
            foreach ($oldItems as $index => $oldItem) {
                $newItem = $newItems->get($index);
                if (!$newItem || $oldItem != $newItem->toArray()) {
                    $changes[] = 'items';
                    break;
                }
            }
        }

        return $changes;
    }
}
