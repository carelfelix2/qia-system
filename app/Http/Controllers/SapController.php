<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;

class SapController extends Controller
{
    public function index()
    {
        return view('sap.dashboard');
    }

    public function quotations(Request $request)
    {
        $query = Quotation::with('quotationItems', 'creator');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', '%' . $search . '%')
                  ->orWhere('sap_number', 'like', '%' . $search . '%');
            });
        }

        // Sort by date
        $sortOrder = $request->get('sort', 'desc');
        $query->orderBy('created_at', $sortOrder);

        $quotations = $query->paginate(10);

        return view('sap.daftar-penawaran', compact('quotations'));
    }

    public function updateSapNumber(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'sap_number' => 'nullable|string|max:255',
            'status' => 'required|in:proses,selesai',
            'attachment_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB max
        ]);

        // Store old data for change detection
        $oldData = $quotation->only(['sap_number', 'status', 'attachment_file']);

        // Handle file upload
        if ($request->hasFile('attachment_file')) {
            // Delete old file if exists
            if ($quotation->attachment_file && \Storage::disk('public')->exists($quotation->attachment_file)) {
                \Storage::disk('public')->delete($quotation->attachment_file);
            }

            // Store new file
            $file = $request->file('attachment_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('quotation_attachments', $filename, 'public');
            $validated['attachment_file'] = $path;
        }

        $quotation->update($validated);

        // Detect changes and notify sales if there are updates
        $changes = $this->detectSapChanges($oldData, $quotation->fresh()->only(['sap_number', 'status', 'attachment_file']));
        if (!empty($changes)) {
            // Notify the sales person who created the quotation
            $salesPerson = $quotation->creator;
            $salesPerson->notify(new \App\Notifications\QuotationUpdatedNotification($quotation, $changes, 'sap'));
        }

        return redirect()->back()->with('success', 'SAP number, status, and attachment updated successfully!');
    }

    public function revisionLog(Request $request)
    {
        $query = \App\Models\QuotationRevision::with('quotation', 'user')
            ->whereHas('quotation') // Ensure quotation exists
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('quotation', function ($subQ) use ($search) {
                    $subQ->where('nama_customer', 'like', '%' . $search . '%')
                         ->orWhere('sap_number', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $revisions = $query->paginate(15);

        return view('sap.log-perubahan', compact('revisions'));
    }

    public function daftarPo(Request $request)
    {
        // Get latest PO file per quotation
        $poFiles = \App\Models\POFile::with('quotation', 'uploader')
            ->whereIn('id', function($sub) {
                $sub->select(\DB::raw('MAX(id)'))
                    ->from('po_files')
                    ->groupBy('quotation_id');
            })
            ->whereHas('quotation') // Ensure quotation exists
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $poFiles->where(function ($q) use ($search) {
                $q->whereHas('quotation', function ($subQ) use ($search) {
                    $subQ->where('nama_customer', 'like', '%' . $search . '%')
                         ->orWhere('sap_number', 'like', '%' . $search . '%');
                })
                ->orWhereHas('uploader', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $poFiles = $poFiles->paginate(15);

        return view('sap.daftar-po', compact('poFiles'));
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('quotationItems', 'creator', 'poFiles.uploader');
        return view('sap.quotation-detail', compact('quotation'));
    }

    private function detectSapChanges($oldData, $newData)
    {
        $changes = [];

        $fieldsToCheck = ['sap_number', 'status', 'attachment_file'];

        foreach ($fieldsToCheck as $field) {
            if ($oldData[$field] !== $newData[$field]) {
                $changes[] = $field;
            }
        }

        return $changes;
    }
}
