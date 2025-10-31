@extends('layouts.sap')
@section('title', 'Quotation Detail - SAP Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quotation Detail</h3>
                <div class="card-actions">
                    <a href="{{ route('sales.daftar-po') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <line x1="5" y1="12" x2="11" y2="18" />
                            <line x1="5" y1="12" x2="11" y2="6" />
                        </svg>
                        Back to Daftar PO
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Quotation Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>Quotation Information</h4>
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>SAP Number:</strong></td>
                                <td>{{ $quotation->sap_number ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Customer:</strong></td>
                                <td>{{ $quotation->nama_customer }}</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>{{ $quotation->alamat_customer }}</td>
                            </tr>
                            <tr>
                                <td><strong>Sales Person:</strong></td>
                                <td>{{ $quotation->sales_person }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge {{ $quotation->status === 'selesai' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($quotation->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Created By:</strong></td>
                                <td>{{ $quotation->creator->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created At:</strong></td>
                                <td>{{ $quotation->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Additional Information</h4>
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Jenis Penawaran:</strong></td>
                                <td>{{ $quotation->jenis_penawaran }}</td>
                            </tr>
                            <tr>
                                <td><strong>Format Layout:</strong></td>
                                <td>{{ $quotation->format_layout }}</td>
                            </tr>
                            <tr>
                                <td><strong>Diskon:</strong></td>
                                <td>{{ $quotation->diskon ? $quotation->diskon . '%' : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pembayaran:</strong></td>
                                <td>{{ $quotation->pembayaran }}</td>
                            </tr>
                            <tr>
                                <td><strong>Stok:</strong></td>
                                <td>{{ $quotation->stok }}</td>
                            </tr>
                            <tr>
                                <td><strong>Keterangan:</strong></td>
                                <td>{{ $quotation->keterangan_tambahan ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Quotation Items -->
                <h4>Quotation Items</h4>
                <div class="table-responsive draggable-table mb-4">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Alat</th>
                                <th>Tipe Alat</th>
                                <th>Merk</th>
                                <th>Part Number</th>
                                <th>Kategori Harga</th>
                                <th>Harga</th>
                                <th>PPN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotation->quotationItems as $item)
                            <tr>
                                <td>{{ $item->nama_alat }}</td>
                                <td>{{ $item->tipe_alat }}</td>
                                <td>{{ $item->merk ?: '-' }}</td>
                                <td>{{ $item->part_number }}</td>
                                <td>{{ $item->kategori_harga }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->ppn }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- PO Files -->
                @if($quotation->poFiles->isNotEmpty())
                <h4>PO Files</h4>
                <div class="table-responsive draggable-table">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Uploaded By</th>
                                <th>Uploaded At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotation->poFiles as $poFile)
                            <tr>
                                <td>{{ basename($poFile->file_path) }}</td>
                                <td>{{ $poFile->uploader->name }}</td>
                                <td>{{ $poFile->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ Storage::url($poFile->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                            <line x1="9" y1="9" x2="10" y2="9" />
                                            <line x1="9" y1="13" x2="15" y2="13" />
                                            <line x1="9" y1="17" x2="15" y2="17" />
                                        </svg>
                                        Download
                                    </a>
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
    // Draggable table functionality
    const draggableTables = document.querySelectorAll('.draggable-table');
    draggableTables.forEach(draggableTable => {
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
});
</script>
@endsection
