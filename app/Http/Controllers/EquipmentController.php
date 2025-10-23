<?php

namespace App\Http\Controllers;

use App\Imports\EquipmentImport;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('search');
        $equipment = Equipment::when($query, function ($q) use ($query) {
            $q->where('nama_alat', 'like', "%{$query}%")
              ->orWhere('tipe_alat', 'like', "%{$query}%")
              ->orWhere('merk', 'like', "%{$query}%")
              ->orWhere('part_number', 'like', "%{$query}%");
        })->paginate(20);
        return view('admin.equipment.index', compact('equipment'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new EquipmentImport, $request->file('file'));

        return redirect()->back()->with('success', 'Equipment data imported successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'tipe_alat' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'part_number' => 'required|string|max:255|unique:equipment',
            'harga_retail' => 'nullable|numeric|min:0',
            'harga_inaproc' => 'nullable|numeric|min:0',
            'harga_sebelum_ppn' => 'nullable|numeric|min:0',
        ]);

        Equipment::create($request->all());

        return redirect()->back()->with('success', 'Equipment added successfully.');
    }

    public function edit(Equipment $equipment)
    {
        return response()->json($equipment);
    }

    public function update(Request $request, Equipment $equipment)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'tipe_alat' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'part_number' => 'required|string|max:255|unique:equipment,part_number,' . $equipment->id,
            'harga_retail' => 'nullable|numeric|min:0',
            'harga_inaproc' => 'nullable|numeric|min:0',
            'harga_sebelum_ppn' => 'nullable|numeric|min:0',
        ]);

        $equipment->update($request->all());

        return redirect()->back()->with('success', 'Equipment updated successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $equipment = Equipment::where('nama_alat', 'like', "%{$query}%")
            ->orWhere('tipe_alat', 'like', "%{$query}%")
            ->orWhere('merk', 'like', "%{$query}%")
            ->orWhere('part_number', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($equipment);
    }
}
