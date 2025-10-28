@extends('layouts.sales')
@section('title', 'Daftar Penawaran - Sales Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Penawaran</h3>
            </div>
            <div class="card-body">
                <!-- Search Form and Delete Button -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <form method="GET" action="{{ route('sales.daftar-penawaran') }}" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama customer, sales person, jenis penawaran, atau status..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('sales.daftar-penawaran') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                        @endif
                    </form>

                    <!-- Delete Selected Button -->
                    <button type="button" class="btn btn-danger" id="delete-selected-btn" style="display: none;" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="4" y1="7" x2="20" y2="7" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                        Delete Selected
                    </button>
                </div>

                @if($quotations->isEmpty())
                                        <div class="text-center py-4">
                                            <p class="text-muted">Belum ada penawaran yang dibuat.</p>
                                            <a href="{{ route('sales.input-penawaran.create') }}" class="btn btn-primary">Buat Penawaran Baru</a>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-vcenter">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" id="select-all-checkbox" class="form-check-input">
                                                        </th>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Sales Person</th>
                                                        <th>Jenis Penawaran</th>
                                                        <th>Nama Customer</th>
                                                        <th>Diskon</th>
                                                        <th>Kategori Harga</th>
                                                        <th>Items</th>
                                                        <th>Status</th>
                                                        <th>No SAP</th>
                                                        <th>Lampiran</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($quotations as $index => $quotation)
                                                    <tr>
                                                        <td>
                                                            @if($quotation->status !== 'selesai')
                                                                <input type="checkbox" class="quotation-checkbox form-check-input" value="{{ $quotation->id }}">
                                                            @endif
                                                        </td>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $quotation->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>{{ $quotation->sales_person }}</td>
                                                        <td>{{ $quotation->jenis_penawaran }}</td>
                                                        <td>{{ $quotation->nama_customer }}</td>
                                                        <td>{{ $quotation->diskon ? $quotation->diskon . '%' : '-' }}</td>
                                                        <td>{{ $quotation->quotationItems->first()?->kategori_harga ?? '-' }}</td>
                                                        <td>
                                                            @if($quotation->quotationItems->count() > 0)
                                                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#items-{{ $quotation->id }}" aria-expanded="false" aria-controls="items-{{ $quotation->id }}">
                                                                    Tampilkan Alat ({{ $quotation->quotationItems->count() }})
                                                                </button>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($quotation->status === 'selesai')
                                                                <span class="badge bg-success me-1"></span>Selesai
                                                            @else
                                                                <span class="badge bg-warning me-1"></span>Proses
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($quotation->sap_number)
                                                                {{ $quotation->sap_number }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($quotation->attachment_file)
                                                                <a href="{{ Storage::url($quotation->attachment_file) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                                        <line x1="9" y1="9" x2="10" y2="9" />
                                                                        <line x1="9" y1="13" x2="15" y2="13" />
                                                                        <line x1="9" y1="17" x2="15" y2="17" />
                                                                    </svg>
                                                                    Lihat File
                                                                </a>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($quotation->status === 'proses')
                                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal" data-quotation-id="{{ $quotation->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                        <path d="M16 5l3 3" />
                                                                    </svg>
                                                                    Edit
                                                                </button>
                                                            @elseif($quotation->status === 'selesai')
                                                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#uploadPoModal" data-quotation-id="{{ $quotation->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                                        <line x1="12" y1="11" x2="12" y2="17" />
                                                                        <line x1="9" y1="14" x2="15" y2="14" />
                                                                    </svg>
                                                                    Upload PO
                                                                </button>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="14" class="p-0">
                                                            <div class="collapse" id="items-{{ $quotation->id }}">
                                                                <div class="card card-body border-0">
                                                                    <h6>Daftar Alat:</h6>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm table-daftar-alat">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Nama Alat</th>
                                                                                    <th>Tipe Alat</th>
                                                                                    <th>Merk</th>
                                                                                    <th>Part Number</th>
                                                                                    <th>Kategori Harga</th>
                                                                                    <th>Harga</th>
                                                                                    <th>Diskon</th>
                                                                                    <th>PPN</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($quotation->quotationItems as $item)
                                                                                <tr>
                                                                                    <td>{{ $item->nama_alat }}</td>
                                                                                    <td>{{ $item->tipe_alat }}</td>
                                                                                    <td>{{ $item->merk }}</td>
                                                                                    <td>{{ $item->part_number }}</td>
                                                                                    <td>{{ $item->kategori_harga }}</td>
                                                                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                                                    <td>{{ $quotation->diskon ? $quotation->diskon . '%' : '-' }}</td>
                                                                                    <td>{{ $item->ppn }}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    @if($quotation->keterangan_tambahan)
                                                                        <div class="mt-3">
                                                                            <h6>Keterangan Tambahan:</h6>
                                                                            <p class="text-muted">{{ $quotation->keterangan_tambahan }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Penawaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Sales</label>
                        <input type="text" name="sales_person" class="form-control" value="{{ auth()->user()->name }}" readonly required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis penawaran</label>
                            <select name="jenis_penawaran" class="form-select" required>
                                <option value="">Pilih Jenis Penawaran</option>
                                <option value="Alat baru">Alat baru</option>
                                <option value="Re-kalibrasi">Re-kalibrasi</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Sewa Alat">Sewa Alat</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Format Layout</label>
                            <select name="format_layout" class="form-select" required>
                                <option value="">Pilih Format Layout</option>
                                <option value="With total">With total</option>
                                <option value="Without total">Without total</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Customer</label>
                        <input type="text" name="nama_customer" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Customer</label>
                        <textarea name="alamat_customer" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Equipment Details</label>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" id="edit_equipment_search" class="form-control" placeholder="Search equipment...">
                                <div id="edit_equipment_suggestions" class="dropdown-menu" style="display: none; position: absolute; z-index: 1000; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; background: white;"></div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="edit_add_equipment_btn" class="btn btn-secondary w-100">Add Equipment</button>
                            </div>
                        </div>
                        <div class="row" id="edit_equipment_form" style="display: none;">
                            <div class="col-md-2">
                                <input type="text" id="edit_nama_alat_input" class="form-control" placeholder="Nama Alat">
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="edit_tipe_alat_input" class="form-control" placeholder="Tipe Alat">
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="edit_merk_input" class="form-control" placeholder="Merk">
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="edit_part_number_input" class="form-control" placeholder="Part Number Alat">
                            </div>
                            <div class="col-md-2">
                                <select id="edit_kategori_harga_input" class="form-select">
                                    <option value="">Kategori Harga</option>
                                    <option value="harga_retail">Harga Retail</option>
                                    <option value="harga_inaproc">Harga Inaproc</option>
                                    <option value="harga_sebelum_ppn">Harga Sebelum PPN</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" id="edit_harga_input" class="form-control" placeholder="Harga" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <table class="table table-bordered" id="edit_equipment_table">
                            <thead>
                                <tr>
                                    <th>Nama Alat</th>
                                    <th>Tipe Alat</th>
                                    <th>Merk</th>
                                    <th>Part Number</th>
                                    <th>Kategori Harga</th>
                                    <th>Harga</th>
                                    <th>PPN</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="edit_equipment_table_body">
                                <!-- Equipment rows will be added here -->
                            </tbody>
                        </table>
                    </div>
                    <!-- Hidden inputs for form submission -->
                    <div id="edit_hidden_inputs_container"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Diskon (%)</label>
                            <input type="number" name="diskon" class="form-control" min="0" max="100" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pembayaran</label>
                            <select name="pembayaran" class="form-select" id="edit_pembayaran-select" required>
                                <option value="">Pilih Pembayaran</option>
                                <option value="30% DP, 70% Sisanya sebelum delivery">30% DP, 70% Sisanya sebelum delivery</option>
                                <option value="100% Setelah barang diterima">100% Setelah barang diterima</option>
                                <option value="TT In Advance">TT In Advance</option>
                            </select>
                            <input type="text" name="pembayaran_other" id="edit_pembayaran-other" class="form-control mt-2" placeholder="Specify other payment" style="display: none;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok Barang / Lama Pengerjaan</label>
                        <select name="stok" class="form-select" id="edit_stok-select" required>
                            <option value="">Pilih Stok</option>
                            <option value="Ready stock tidak mengikat">Ready stock tidak mengikat</option>
                            <option value="Indent 10-12 Minggu (Setelah DP diterima)">Indent 10-12 Minggu (Setelah DP diterima)</option>
                            <option value="Indent 10-12 Minggu">Indent 10-12 Minggu</option>
                        </select>
                        <input type="text" name="stok_other" id="edit_stok-other" class="form-control mt-2" placeholder="Specify other stock info" style="display: none;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan_tambahan" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="editForm" class="btn btn-primary">Update Penawaran</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus penawaran yang dipilih? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload PO Modal -->
<div class="modal fade" id="uploadPoModal" tabindex="-1" aria-labelledby="uploadPoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadPoModalLabel">Upload File PO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadPoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="po_file" class="form-label">Pilih File PO</label>
                        <input type="file" class="form-control" id="po_file" name="po_file" accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="form-text">Format yang didukung: PDF, JPG, PNG. Maksimal 5MB.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="uploadPoBtn">Upload</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const quotationCheckboxes = document.querySelectorAll('.quotation-checkbox');
    const deleteSelectedBtn = document.getElementById('delete-selected-btn');
    const deleteForm = document.getElementById('delete-form');

    // Handle select all checkbox
    selectAllCheckbox.addEventListener('change', function() {
        quotationCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateDeleteButtonVisibility();
    });

    // Handle individual checkboxes
    quotationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.quotation-checkbox:checked');
            selectAllCheckbox.checked = checkedBoxes.length === quotationCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < quotationCheckboxes.length;
            updateDeleteButtonVisibility();
        });
    });

    // Update delete button visibility
    function updateDeleteButtonVisibility() {
        const checkedBoxes = document.querySelectorAll('.quotation-checkbox:checked');
        deleteSelectedBtn.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
    }

    // Handle delete form submission
    deleteForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const checkedBoxes = document.querySelectorAll('.quotation-checkbox:checked');
        const quotationIds = Array.from(checkedBoxes).map(cb => cb.value);

        if (quotationIds.length === 0) {
            alert('Pilih setidaknya satu penawaran untuk dihapus.');
            return;
        }

        // Create hidden inputs for quotation IDs
        quotationIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'quotation_ids[]';
            input.value = id;
            deleteForm.appendChild(input);
        });

        // Set form action based on single or multiple delete
        if (quotationIds.length === 1) {
            deleteForm.action = '{{ route("sales.quotation.destroy", ":id") }}'.replace(':id', quotationIds[0]);
            deleteForm.method = 'POST';
            // Add method spoofing for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            deleteForm.appendChild(methodInput);
        } else {
            deleteForm.action = '{{ route("sales.quotations.destroy-multiple") }}';
            deleteForm.method = 'POST';
        }

        // Submit the form
        deleteForm.submit();
    });

    // Edit modal functionality
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');

    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const quotationId = button.getAttribute('data-quotation-id');

        // Fetch quotation data
        fetch(`/sales/quotation/${quotationId}/edit`)
            .then(response => {
                if (!response.ok) {
                    if (response.status === 403) {
                        return response.text().then(text => {
                            throw new Error(text || 'You do not have permission to edit this quotation.');
                        });
                    } else {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                }
                return response.json();
            })
            .then(data => {
                // Populate form fields
                editForm.querySelector('[name="jenis_penawaran"]').value = data.jenis_penawaran;
                editForm.querySelector('[name="format_layout"]').value = data.format_layout;
                editForm.querySelector('[name="nama_customer"]').value = data.nama_customer;
                editForm.querySelector('[name="alamat_customer"]').value = data.alamat_customer;
                editForm.querySelector('[name="diskon"]').value = data.diskon || '';
                editForm.querySelector('[name="pembayaran"]').value = data.pembayaran;
                editForm.querySelector('[name="pembayaran_other"]').value = data.pembayaran_other || '';
                editForm.querySelector('[name="stok"]').value = data.stok;
                editForm.querySelector('[name="stok_other"]').value = data.stok_other || '';
                editForm.querySelector('[name="keterangan_tambahan"]').value = data.keterangan_tambahan || '';

                // Handle conditional fields
                if (data.pembayaran === 'Other:') {
                    document.getElementById('edit_pembayaran-other').style.display = 'block';
                }
                if (data.stok === 'Other:') {
                    document.getElementById('edit_stok-other').style.display = 'block';
                }

                // Populate equipment table
                const tableBody = document.getElementById('edit_equipment_table_body');
                const hiddenContainer = document.getElementById('edit_hidden_inputs_container');
                tableBody.innerHTML = '';
                hiddenContainer.innerHTML = '';

                data.items.forEach((item, index) => {
                    const row = tableBody.insertRow();
                    row.innerHTML = `
                        <td>${item.nama_alat}</td>
                        <td>${item.tipe_alat}</td>
                        <td>${item.merk || ''}</td>
                        <td>${item.part_number}</td>
                        <td>${item.kategori_harga}</td>
                        <td><input type="number" class="form-control" name="items[${index}][harga]" value="${item.harga}" step="0.01" min="0" required></td>
                        <td>
                            <select class="form-select" name="items[${index}][ppn]" required>
                                <option value="Ya" ${item.ppn === 'Ya' ? 'selected' : ''}>Ya</option>
                                <option value="Tidak" ${item.ppn === 'Tidak' ? 'selected' : ''}>Tidak</option>
                            </select>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm edit_remove-equipment">Remove</button></td>
                    `;

                    // Add hidden inputs for non-editable fields
                    hiddenContainer.innerHTML += `
                        <input type="hidden" name="items[${index}][nama_alat]" value="${item.nama_alat}">
                        <input type="hidden" name="items[${index}][tipe_alat]" value="${item.tipe_alat}">
                        <input type="hidden" name="items[${index}][merk]" value="${item.merk || ''}">
                        <input type="hidden" name="items[${index}][part_number]" value="${item.part_number}">
                        <input type="hidden" name="items[${index}][kategori_harga]" value="${item.kategori_harga}">
                    `;
                });

                // Set form action
                editForm.action = `/sales/quotation/${quotationId}`;
            })
            .catch(error => {
                console.error('Error loading quotation data:', error);
                alert(error.message || 'Error loading quotation data');
            });
    });

    // Reset modal when hidden
    editModal.addEventListener('hidden.bs.modal', function() {
        editForm.reset();
        document.getElementById('edit_equipment_table_body').innerHTML = '';
        document.getElementById('edit_hidden_inputs_container').innerHTML = '';
        document.getElementById('edit_pembayaran-other').style.display = 'none';
        document.getElementById('edit_stok-other').style.display = 'none';
    });

    // Edit equipment search functionality
    document.getElementById('edit_equipment_search').addEventListener('input', function() {
        const query = this.value.trim();
        const suggestions = document.getElementById('edit_equipment_suggestions');

        if (query.length < 2) {
            suggestions.style.display = 'none';
            return;
        }

        fetch(`/api/equipment/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                suggestions.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const li = document.createElement('a');
                        li.className = 'dropdown-item';
                        li.href = '#';
                        li.textContent = `${item.nama_alat} - ${item.tipe_alat} - ${item.merk} (${item.part_number})`;
                        li.addEventListener('click', function(e) {
                            e.preventDefault();
                            window.editCurrentEquipmentItem = item;
                            editFillEquipmentForm(item);
                            suggestions.style.display = 'none';
                            document.getElementById('edit_equipment_search').value = '';
                        });
                        suggestions.appendChild(li);
                    });
                    suggestions.style.display = 'block';
                } else {
                    suggestions.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching equipment:', error);
                suggestions.style.display = 'none';
            });
    });

    function editFillEquipmentForm(item) {
        document.getElementById('edit_nama_alat_input').value = item.nama_alat || '';
        document.getElementById('edit_tipe_alat_input').value = item.tipe_alat || '';
        document.getElementById('edit_merk_input').value = item.merk || '';
        document.getElementById('edit_part_number_input').value = item.part_number || '';
        document.getElementById('edit_kategori_harga_input').value = 'manual';
        document.getElementById('edit_harga_input').value = '';
        document.getElementById('edit_harga_input').readOnly = false;
        document.getElementById('edit_equipment_form').style.display = 'flex';
    }

    // Add event listener for edit kategori_harga change
    document.getElementById('edit_kategori_harga_input').addEventListener('change', function() {
        const selected = this.value;
        const hargaInput = document.getElementById('edit_harga_input');
        if (selected === 'manual') {
            hargaInput.readOnly = false;
            hargaInput.value = '';
        } else {
            const item = window.editCurrentEquipmentItem;
            if (item && item[selected]) {
                hargaInput.value = item[selected];
                hargaInput.readOnly = true;
            } else {
                hargaInput.readOnly = false;
                hargaInput.value = '';
            }
        }
    });

    document.getElementById('edit_add_equipment_btn').addEventListener('click', function() {
        const namaAlat = document.getElementById('edit_nama_alat_input').value.trim();
        const tipeAlat = document.getElementById('edit_tipe_alat_input').value.trim();
        const merk = document.getElementById('edit_merk_input').value.trim();
        const partNumber = document.getElementById('edit_part_number_input').value.trim();
        const kategoriHarga = document.getElementById('edit_kategori_harga_input').value;
        const harga = document.getElementById('edit_harga_input').value.trim();

        if (!namaAlat || !tipeAlat || !partNumber || !kategoriHarga || !harga) {
            alert('Please fill in all required fields.');
            return;
        }

        const tableBody = document.getElementById('edit_equipment_table_body');
        const hiddenContainer = document.getElementById('edit_hidden_inputs_container');
        const index = tableBody.rows.length;

        const row = tableBody.insertRow();
        row.innerHTML = `
            <td>${namaAlat}</td>
            <td>${tipeAlat}</td>
            <td>${merk}</td>
            <td>${partNumber}</td>
            <td>${kategoriHarga}</td>
            <td><input type="number" class="form-control" name="items[${index}][harga]" value="${harga}" step="0.01" min="0" required></td>
            <td>
                <select class="form-select" name="items[${index}][ppn]" required>
                    <option value="">Pilih</option>
                    <option value="Ya">Ya</option>
                    <option value="Tidak">Tidak</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm edit_remove-equipment">Remove</button></td>
        `;

        hiddenContainer.innerHTML += `
            <input type="hidden" name="items[${index}][nama_alat]" value="${namaAlat}">
            <input type="hidden" name="items[${index}][tipe_alat]" value="${tipeAlat}">
            <input type="hidden" name="items[${index}][merk]" value="${merk}">
            <input type="hidden" name="items[${index}][part_number]" value="${partNumber}">
            <input type="hidden" name="items[${index}][kategori_harga]" value="${kategoriHarga}">
        `;

        // Clear input fields
        document.getElementById('edit_nama_alat_input').value = '';
        document.getElementById('edit_tipe_alat_input').value = '';
        document.getElementById('edit_merk_input').value = '';
        document.getElementById('edit_part_number_input').value = '';
        document.getElementById('edit_kategori_harga_input').value = '';
        document.getElementById('edit_harga_input').value = '';
        document.getElementById('edit_equipment_form').style.display = 'none';
    });

    // Event delegation for edit remove buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit_remove-equipment')) {
            const row = e.target.closest('tr');
            const index = Array.from(row.parentNode.children).indexOf(row);

            row.remove();

            // Remove corresponding hidden inputs
            const hiddenContainer = document.getElementById('edit_hidden_inputs_container');
            const hiddenInputs = hiddenContainer.querySelectorAll(`input[name^="items[${index}]"]`);
            hiddenInputs.forEach(input => input.remove());

            // Re-index remaining items
            editReindexEquipment();
        }
    });

    function editReindexEquipment() {
        const rows = document.querySelectorAll('#edit_equipment_table_body tr');
        const hiddenContainer = document.getElementById('edit_hidden_inputs_container');
        hiddenContainer.innerHTML = '';

        rows.forEach((row, newIndex) => {
            const cells = row.querySelectorAll('td');
            const inputs = row.querySelectorAll('input, select');

            inputs.forEach(input => {
                const name = input.name.replace(/\[\d+\]/, `[${newIndex}]`);
                input.name = name;
            });

            const namaAlat = cells[0].textContent;
            const tipeAlat = cells[1].textContent;
            const merk = cells[2].textContent;
            const partNumber = cells[3].textContent;
            const kategoriHarga = cells[4].textContent;

            hiddenContainer.innerHTML += `
                <input type="hidden" name="items[${newIndex}][nama_alat]" value="${namaAlat}">
                <input type="hidden" name="items[${newIndex}][tipe_alat]" value="${tipeAlat}">
                <input type="hidden" name="items[${newIndex}][merk]" value="${merk}">
                <input type="hidden" name="items[${newIndex}][part_number]" value="${partNumber}">
                <input type="hidden" name="items[${newIndex}][kategori_harga]" value="${kategoriHarga}">
            `;
        });
    }

    document.getElementById('edit_pembayaran-select').addEventListener('change', function() {
        const otherInput = document.getElementById('edit_pembayaran-other');
        if (this.value === 'Other:') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
        }
    });

    document.getElementById('edit_stok-select').addEventListener('change', function() {
        const otherInput = document.getElementById('edit_stok-other');
        if (this.value === 'Other:') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
        }
    });

    // Upload PO modal functionality
    const uploadPoModal = document.getElementById('uploadPoModal');
    const uploadPoForm = document.getElementById('uploadPoForm');
    const uploadPoBtn = document.getElementById('uploadPoBtn');

    uploadPoModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const quotationId = button.getAttribute('data-quotation-id');
        uploadPoForm.setAttribute('data-quotation-id', quotationId);
    });

    uploadPoBtn.addEventListener('click', function() {
        const quotationId = uploadPoForm.getAttribute('data-quotation-id');
        const formData = new FormData(uploadPoForm);

        fetch(`/sales/quotation/${quotationId}/upload-po`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File PO berhasil diupload!');
                uploadPoModal.querySelector('.btn-close').click();
                location.reload(); // Refresh to show updated data
            } else {
                alert('Error: ' + (data.error || 'Upload gagal'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat upload file.');
        });
    });

    // Reset form when modal is hidden
    uploadPoModal.addEventListener('hidden.bs.modal', function() {
        uploadPoForm.reset();
        uploadPoForm.removeAttribute('data-quotation-id');
    });
});
</script>
@endsection
