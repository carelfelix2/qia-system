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
