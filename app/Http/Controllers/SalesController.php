<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
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
            'nama_alat' => 'required|string|max:255',
            'part_number' => 'required|string|max:255',
            'kategori_harga' => 'required|in:HARGA INAPROC 2025,HARGA RETAIL 2025,NON E-KATALOG (CUSTOM)',
            'harga' => 'required|numeric|min:0',
            'ppn' => 'required|in:YA,Tidak',
            'diskon' => 'nullable|numeric|min:0|max:100',
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

        Quotation::create($validated);

        return redirect()->route('sales.input-penawaran.create')->with('success', 'Quotation submitted successfully!');
    }

    public function quotations()
    {
        $quotations = Quotation::where('created_by', auth()->id())->latest()->get();
        return view('sales.daftar-penawaran', compact('quotations'));
    }
}
