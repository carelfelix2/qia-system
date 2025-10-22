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
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="p-0">
                                                            <div class="collapse" id="items-{{ $quotation->id }}">
                                                                <div class="card card-body border-0">
                                                                    <h6>Daftar Alat:</h6>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm">
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
});
</script>
@endsection
