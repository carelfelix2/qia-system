@extends('layouts.sap')

@section('title', 'Log Perubahan Penawaran')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Log Perubahan Penawaran</h3>
                <div class="card-actions">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama customer, no SAP, atau nama user..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary me-2">Cari</button>
                        <a href="{{ route('sap.log-perubahan') }}" class="btn btn-outline-secondary">Reset</a>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($revisions->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada perubahan pada penawaran.</p>
                    </div>
                @else
                    <div class="table-responsive draggable-table">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>User</th>
                                    <th>Penawaran</th>
                                    <th>Customer</th>
                                    <th>Perubahan</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revisions as $index => $revision)
                                <tr>
                                    <td>{{ $revisions->firstItem() + $index }}</td>
                                    <td>{{ $revision->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $revision->user->name }}</td>
                                    <td>
                                        @if($revision->quotation)
                                            <a href="{{ route('sap.daftar-penawaran') }}?search={{ $revision->quotation->sap_number ?? $revision->quotation->nama_customer }}" class="text-decoration-none">
                                                {{ $revision->quotation->sap_number ? 'SAP: ' . $revision->quotation->sap_number : 'ID: ' . $revision->quotation->id }}
                                            </a>
                                        @else
                                            <span class="text-muted">Penawaran tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td>{{ $revision->quotation ? $revision->quotation->nama_customer : '-' }}</td>
                                    <td>
                                        @if($revision->action === 'updated')
                                            <span class="badge bg-warning">Diperbarui</span>
                                        @elseif($revision->action === 'created')
                                            <span class="badge bg-success">Dibuat</span>
                                        @elseif($revision->action === 'deleted')
                                            <span class="badge bg-danger">Dihapus</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($revision->action) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($revision->old_data && $revision->new_data)
                                            @php
                                                $changes = [];
                                                $oldData = $revision->old_data;
                                                $newData = $revision->new_data;

                                                // Check main quotation fields
                                                $mainFields = [
                                                    'sales_person' => 'Sales Person',
                                                    'jenis_penawaran' => 'Jenis Penawaran',
                                                    'format_layout' => 'Format Layout',
                                                    'nama_customer' => 'Nama Customer',
                                                    'alamat_customer' => 'Alamat Customer',
                                                    'diskon' => 'Diskon',
                                                    'pembayaran' => 'Pembayaran',
                                                    'pembayaran_other' => 'Pembayaran Lain',
                                                    'stok' => 'Stok',
                                                    'stok_other' => 'Stok Lain',
                                                    'keterangan_tambahan' => 'Keterangan Tambahan'
                                                ];

                                                foreach ($mainFields as $field => $label) {
                                                    if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] !== $newData[$field]) {
                                                        $changes[] = $label . ': "' . ($oldData[$field] ?? '-') . '" → "' . ($newData[$field] ?? '-') . '"';
                                                    }
                                                }

                                                // Check if items changed
                                                if (isset($oldData['items']) && isset($newData['items'])) {
                                                    $oldItems = collect($oldData['items']);
                                                    $newItems = collect($newData['items']);

                                                    if ($oldItems->count() !== $newItems->count()) {
                                                        $changes[] = 'Jumlah item berubah: ' . $oldItems->count() . ' → ' . $newItems->count();
                                                    } else {
                                                        // Check each item for changes
                                                        foreach ($oldItems as $index => $oldItem) {
                                                            $newItem = $newItems->get($index);
                                                            if ($newItem && $oldItem != $newItem) {
                                                                $itemChanges = [];
                                                                $itemFields = [
                                                                    'nama_alat' => 'Nama Alat',
                                                                    'tipe_alat' => 'Tipe Alat',
                                                                    'merk' => 'Merk',
                                                                    'part_number' => 'Part Number',
                                                                    'kategori_harga' => 'Kategori Harga',
                                                                    'harga' => 'Harga',
                                                                    'ppn' => 'PPN'
                                                                ];

                                                                foreach ($itemFields as $field => $label) {
                                                                    if (isset($oldItem[$field]) && isset($newItem[$field]) && $oldItem[$field] !== $newItem[$field]) {
                                                                        $itemChanges[] = $label . ': "' . ($oldItem[$field] ?? '-') . '" → "' . ($newItem[$field] ?? '-') . '"';
                                                                    }
                                                                }

                                                                if (!empty($itemChanges)) {
                                                                    $changes[] = 'Item ' . ($index + 1) . ': ' . implode(', ', $itemChanges);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if(!empty($changes))
                                                <div class="text-start">
                                                    <small>
                                                        @foreach($changes as $change)
                                                            <div class="mb-1">{{ $change }}</div>
                                                        @endforeach
                                                    </small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        @elseif($revision->notes)
                                            <span class="text-muted">{{ $revision->notes }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer">
                        <div class="row g-2 justify-content-center justify-content-sm-between">
                            <div class="col-auto d-flex align-items-center">
                                <p class="m-0 text-secondary">Showing <strong>{{ $revisions->firstItem() ?? 0 }} to {{ $revisions->lastItem() ?? 0 }}</strong> of <strong>{{ $revisions->total() }} entries</strong></p>
                            </div>
                            <div class="col-auto">
                                {{ $revisions->appends(request()->query())->links() }}
                            </div>
                        </div>
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
