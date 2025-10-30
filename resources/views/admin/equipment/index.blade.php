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
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                            Add New Equipment
                        </button>
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



                    <div class="table-responsive draggable-table">
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
                                    <th>Actions</th>
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
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#editModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No equipment data available</td>
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

<!-- Add Modal -->
<div class="modal modal-blur fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.equipment.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                                <input type="text" name="nama_alat" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipe Alat <span class="text-danger">*</span></label>
                                <input type="text" name="tipe_alat" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Merk <span class="text-danger">*</span></label>
                                <input type="text" name="merk" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Part Number <span class="text-danger">*</span></label>
                                <input type="text" name="part_number" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Retail</label>
                                <input type="number" name="harga_retail" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Inaproc</label>
                                <input type="number" name="harga_inaproc" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Sebelum PPN</label>
                                <input type="number" name="harga_sebelum_ppn" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add Equipment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal modal-blur fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                                <input type="text" name="nama_alat" id="edit_nama_alat" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipe Alat <span class="text-danger">*</span></label>
                                <input type="text" name="tipe_alat" id="edit_tipe_alat" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Merk <span class="text-danger">*</span></label>
                                <input type="text" name="merk" id="edit_merk" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Part Number <span class="text-danger">*</span></label>
                                <input type="text" name="part_number" id="edit_part_number" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Retail</label>
                                <input type="number" name="harga_retail" id="edit_harga_retail" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Inaproc</label>
                                <input type="number" name="harga_inaproc" id="edit_harga_inaproc" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Harga Sebelum PPN</label>
                                <input type="number" name="harga_sebelum_ppn" id="edit_harga_sebelum_ppn" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Equipment</button>
                </div>
            </form>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const equipmentId = this.getAttribute('data-id');

            // Fetch equipment data
            fetch(`/admin/equipment/${equipmentId}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Populate edit form
                    document.getElementById('edit_nama_alat').value = data.nama_alat;
                    document.getElementById('edit_tipe_alat').value = data.tipe_alat;
                    document.getElementById('edit_merk').value = data.merk;
                    document.getElementById('edit_part_number').value = data.part_number;
                    document.getElementById('edit_harga_retail').value = data.harga_retail || '';
                    document.getElementById('edit_harga_inaproc').value = data.harga_inaproc || '';
                    document.getElementById('edit_harga_sebelum_ppn').value = data.harga_sebelum_ppn || '';

                    // Update form action
                    document.getElementById('editForm').action = `/admin/equipment/${equipmentId}`;
                })
                .catch(error => {
                    console.error('Error fetching equipment data:', error);
                    alert('Error loading equipment data. Please try again.');
                });
        });
    });
});
</script>
<style>
.draggable-table {
    overflow-x: auto;
    cursor: grab;
    user-select: none;
}

.draggable-table:active {
    cursor: grabbing;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const equipmentId = this.getAttribute('data-id');

            // Fetch equipment data
            fetch(`/admin/equipment/${equipmentId}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Populate edit form
                    document.getElementById('edit_nama_alat').value = data.nama_alat;
                    document.getElementById('edit_tipe_alat').value = data.tipe_alat;
                    document.getElementById('edit_merk').value = data.merk;
                    document.getElementById('edit_part_number').value = data.part_number;
                    document.getElementById('edit_harga_retail').value = data.harga_retail || '';
                    document.getElementById('edit_harga_inaproc').value = data.harga_inaproc || '';
                    document.getElementById('edit_harga_sebelum_ppn').value = data.harga_sebelum_ppn || '';

                    // Update form action
                    document.getElementById('editForm').action = `/admin/equipment/${equipmentId}`;
                })
                .catch(error => {
                    console.error('Error fetching equipment data:', error);
                    alert('Error loading equipment data. Please try again.');
                });
        });
    });

    // Draggable table functionality
    const draggableTable = document.querySelector('.draggable-table');
    let isDragging = false;
    let startX;
    let scrollLeft;

    draggableTable.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX - draggableTable.offsetLeft;
        scrollLeft = draggableTable.scrollLeft;
        draggableTable.style.cursor = 'grabbing';
    });

    draggableTable.addEventListener('mouseleave', () => {
        isDragging = false;
        draggableTable.style.cursor = 'grab';
    });

    draggableTable.addEventListener('mouseup', () => {
        isDragging = false;
        draggableTable.style.cursor = 'grab';
    });

    draggableTable.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - draggableTable.offsetLeft;
        const walk = (x - startX) * 2; // Scroll speed multiplier
        draggableTable.scrollLeft = scrollLeft - walk;
    });
});
</script>
@endsection
