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
            'diskon' => 'nullable|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.nama_alat' => 'required|string|max:255',
            'items.*.tipe_alat' => 'required|string|max:255',
            'items.*.merk' => 'nullable|string|max:255',
            'items.*.part_number' => 'required|string|max:255',
            'items.*.kategori_harga' => 'required|in:harga_retail,harga_inaproc,harga_sebelum_ppn,manual',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.ppn' => 'required|in:Ya,Tidak',
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

    public function quotations(Request $request)
    {
        $query = Quotation::with('quotationItems')->where('created_by', auth()->id());

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', '%' . $search . '%')
                  ->orWhere('sales_person', 'like', '%' . $search . '%')
                  ->orWhere('jenis_penawaran', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        $quotations = $query->latest()->get();
        return view('sales.daftar-penawaran', compact('quotations'));
    }

    public function edit(Quotation $quotation)
    {
        // Ensure user can only edit their own quotations
        if ($quotation->created_by !== auth()->id()) {
            abort(403);
        }

        // Only allow editing of quotations with status 'proses'
        if ($quotation->status === 'selesai') {
            abort(403, 'Cannot edit completed quotations');
        }

        $quotation->load('quotationItems');

        return response()->json([
            'jenis_penawaran' => $quotation->jenis_penawaran,
            'format_layout' => $quotation->format_layout,
            'nama_customer' => $quotation->nama_customer,
            'alamat_customer' => $quotation->alamat_customer,
            'diskon' => $quotation->diskon,
            'pembayaran' => $quotation->pembayaran,
            'pembayaran_other' => $quotation->pembayaran_other,
            'stok' => $quotation->stok,
            'stok_other' => $quotation->stok_other,
            'keterangan_tambahan' => $quotation->keterangan_tambahan,
            'items' => $quotation->quotationItems->map(function ($item) {
                return [
                    'nama_alat' => $item->nama_alat,
                    'tipe_alat' => $item->tipe_alat,
                    'merk' => $item->merk,
                    'part_number' => $item->part_number,
                    'kategori_harga' => $item->kategori_harga,
                    'harga' => $item->harga,
                    'ppn' => $item->ppn,
                ];
            })->toArray(),
        ]);
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
            'diskon' => 'nullable|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.nama_alat' => 'required|string|max:255',
            'items.*.tipe_alat' => 'required|string|max:255',
            'items.*.merk' => 'nullable|string|max:255',
            'items.*.part_number' => 'required|string|max:255',
            'items.*.kategori_harga' => 'required|in:harga_retail,harga_inaproc,harga_sebelum_ppn,manual',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.ppn' => 'required|in:Ya,Tidak',
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
                $user->notify(new QuotationUpdatedNotification($quotation, $changes, 'sales'));
            }
        }

        return redirect()->route('sales.daftar-penawaran')->with('success', 'Quotation updated successfully!');
    }

    private function detectChanges($oldData, $newQuotation)
    {
        $changes = [];

        $fieldsToCheck = [
            'sales_person', 'jenis_penawaran', 'format_layout', 'nama_customer',
            'alamat_customer', 'diskon', 'pembayaran', 'pembayaran_other', 'stok',
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

    public function destroy(Quotation $quotation)
    {
        // Ensure user can only delete their own quotations
        if ($quotation->created_by !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow deletion of quotations with status 'proses'
        if ($quotation->status === 'selesai') {
            return redirect()->back()->with('error', 'Cannot delete completed quotations');
        }

        // Delete associated quotation items and revisions
        $quotation->quotationItems()->delete();
        $quotation->revisions()->delete();

        // Delete the quotation
        $quotation->delete();

        return redirect()->route('sales.daftar-penawaran')->with('success', 'Quotation deleted successfully!');
    }

    public function destroyMultiple(Request $request)
    {
        $validated = $request->validate([
            'quotation_ids' => 'required|array|min:1',
            'quotation_ids.*' => 'required|integer|exists:quotations,id',
        ]);

        $quotationIds = $validated['quotation_ids'];
        $userId = auth()->id();

        // Get quotations that belong to the user and are not completed
        $quotations = Quotation::whereIn('id', $quotationIds)
            ->where('created_by', $userId)
            ->where('status', '!=', 'selesai')
            ->get();

        if ($quotations->isEmpty()) {
            return redirect()->back()->with('error', 'No valid quotations found to delete');
        }

        $deletedCount = 0;
        foreach ($quotations as $quotation) {
            // Delete associated quotation items and revisions
            $quotation->quotationItems()->delete();
            $quotation->revisions()->delete();

            // Delete the quotation
            $quotation->delete();
            $deletedCount++;
        }

        return redirect()->route('sales.daftar-penawaran')->with('success', $deletedCount . ' quotation(s) deleted successfully!');
    }
}
