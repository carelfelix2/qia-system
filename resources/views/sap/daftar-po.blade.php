@extends('layouts.sap')
@section('title', 'Daftar PO - SAP Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar PO</h3>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <form method="GET" action="{{ route('sap.daftar-po') }}" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama customer, SAP number, atau uploader..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('sap.daftar-po') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                        @endif
                    </form>
                </div>

                @if($poFiles->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada file PO yang diupload.</p>
                    </div>
                @else
                    <div class="table-responsive draggable-table">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Quotation Number</th>
                                    <th>Customer Name</th>
                                    <th>Uploaded By</th>
                                    <th>File</th>
                                    <th>Uploaded At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($poFiles as $index => $poFile)
                                <tr>
                                    <td>{{ $poFiles->firstItem() + $index }}</td>
                                    <td>
                                        @if($poFile->quotation->sap_number)
                                            {{ $poFile->quotation->sap_number }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $poFile->quotation->nama_customer }}</td>
                                    <td>{{ $poFile->uploader->name }}</td>
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
                                    <td>{{ $poFile->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('sap.quotation.show', $poFile->quotation) }}" class="btn btn-sm btn-outline-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="2" />
                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                            </svg>
                                            View Quotation
                                        </a>
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
                                <p class="m-0 text-secondary">Showing <strong>{{ $poFiles->firstItem() ?? 0 }} to {{ $poFiles->lastItem() ?? 0 }}</strong> of <strong>{{ $poFiles->total() }} entries</strong></p>
                            </div>
                            <div class="col-auto">
                                {{ $poFiles->appends(request()->query())->links() }}
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
