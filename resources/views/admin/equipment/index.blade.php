@extends('layouts.admin')

@section('title', 'Equipment Management')

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Equipment Management</h3>
                    <div class="card-actions d-flex align-items-center gap-2">
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('admin.equipment.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search equipment..." value="{{ request('search') }}" style="width: 200px;">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>
                                </a>
                            @endif
                        </form>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                            Import Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif



                    <div class="table-responsive">
                        <table class="table table-vcenter table-mobile-md card-table">
                            <thead>
                                <tr>
                                    <th>Nama Alat</th>
                                    <th>Tipe Alat</th>
                                    <th>Merk</th>
                                    <th>Harga Retail</th>
                                    <th>Harga Inaproc</th>
                                    <th>Harga Sebelum PPN</th>
                                    <th>Part Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($equipment as $item)
                                    <tr>
                                        <td>{{ $item->nama_alat }}</td>
                                        <td>{{ $item->tipe_alat }}</td>
                                        <td>{{ $item->merk }}</td>
                                        <td>{{ $item->harga_retail ? 'Rp ' . number_format($item->harga_retail, 0, ',', '.') : '-' }}</td>
                                        <td>{{ $item->harga_inaproc ? 'Rp ' . number_format($item->harga_inaproc, 0, ',', '.') : '-' }}</td>
                                        <td>{{ $item->harga_sebelum_ppn ? 'Rp ' . number_format($item->harga_sebelum_ppn, 0, ',', '.') : '-' }}</td>
                                        <td>{{ $item->part_number }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No equipment data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $equipment->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal modal-blur fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Equipment Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.equipment.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Excel File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            Upload an Excel file with columns: nama_alat, tipe_alat, merk, part_number, harga_retail, harga_inaproc, harga_sebelum_ppn
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
