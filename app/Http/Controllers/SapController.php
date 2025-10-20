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

        return redirect()->back()->with('success', 'SAP number, status, and attachment updated successfully!');
    }
}
