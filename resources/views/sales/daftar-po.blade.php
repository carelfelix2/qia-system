@extends('layouts.sales')
@section('title', 'Daftar PO - Sales Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar PO</h3>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <div class="mb-3">
                    <form method="GET" action="{{ route('sales.daftar-po') }}" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama customer, sales person, jenis penawaran, atau no SAP..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('sales.daftar-po') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                        @endif
                    </form>
                </div>

                @if($quotations->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada PO yang diupload.</p>
                    </div>
                @else
                    <div class="table-responsive draggable-table">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Sales Person</th>
                                    <th>Jenis Penawaran</th>
                                    <th>Nama Customer</th>
                                    <th>No SAP</th>
                                    <th>File PO</th>
                                    <th>Items</th>
                                    <th>Keterangan Tambahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotations as $index => $quotation)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $quotation->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $quotation->sales_person }}</td>
                                    <td>{{ $quotation->jenis_penawaran }}</td>
                                    <td>{{ $quotation->nama_customer }}</td>
                                    <td>
                                        @if($quotation->sap_number)
                                            {{ $quotation->sap_number }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($quotation->poFiles->count() > 0)
                                            @foreach($quotation->poFiles as $poFile)
                                            <div class="d-flex align-items-center mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                    <line x1="9" y1="9" x2="10" y2="9" />
                                                    <line x1="9" y1="13" x2="15" y2="13" />
                                                    <line x1="9" y1="17" x2="15" y2="17" />
                                                </svg>
                                                <div>
                                                    <a href="{{ Storage::url($poFile->file_path) }}" target="_blank" class="text-decoration-none">
                                                        {{ basename($poFile->file_path) }}
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">Uploaded by {{ $poFile->uploader->name }} on {{ $poFile->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
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
                                        @if($quotation->keterangan_tambahan)
                                            {{ $quotation->keterangan_tambahan }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="9" class="p-0">
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
