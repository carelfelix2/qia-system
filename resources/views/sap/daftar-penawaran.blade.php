@extends('layouts.sap')

@section('title', 'Daftar Penawaran')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Penawaran SAP</h3>
                <div class="card-actions">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama customer atau no SAP..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary me-2">Cari</button>
                        <a href="{{ route('sap.daftar-penawaran') }}" class="btn btn-outline-secondary">Reset</a>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($quotations->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada penawaran yang dibuat oleh sales.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                                <tr>
                                    <th>No</th>
                                    <th>
                                        Tanggal
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none ms-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <polyline points="6 15 12 9 18 15" />
                                            </svg>
                                        </a>
                                    </th>
                                    <th>Sales Person</th>
                                    <th>Jenis Penawaran</th>
                                    <th>Format Layout</th>
                                    <th>Nama Customer</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>No SAP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotations as $index => $quotation)
                                <tr>
                                    <td>{{ $quotations->firstItem() + $index }}</td>
                                    <td>{{ $quotation->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $quotation->sales_person }}</td>
                                    <td>{{ $quotation->jenis_penawaran }}</td>
                                    <td>{{ $quotation->format_layout }}</td>
                                    <td>{{ $quotation->nama_customer }}</td>
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
                                        <span class="badge {{ $quotation->status === 'selesai' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($quotation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($quotation->sap_number)
                                            {{ $quotation->sap_number }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-{{ $quotation->id }}">
                                            Input SAP
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="p-0">
                                        <div class="collapse" id="items-{{ $quotation->id }}">
                                            <div class="card card-body border-0">
                                                <div class="row">
                                                    <div class="col-12">
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
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                @if($quotation->keterangan_tambahan)
                                                                    <h6>Keterangan Tambahan:</h6>
                                                                    <p class="text-muted">{{ $quotation->keterangan_tambahan }}</p>
                                                                @else
                                                                    <h6>Keterangan Tambahan:</h6>
                                                                    <p class="text-muted">-</p>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Lampiran File:</h6>
                                                                @if($quotation->attachment_file)
                                                                    <div class="d-flex align-items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-2 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                                            <line x1="9" y1="9" x2="10" y2="9" />
                                                                            <line x1="9" y1="13" x2="15" y2="13" />
                                                                            <line x1="9" y1="17" x2="15" y2="17" />
                                                                        </svg>
                                                                        <div>
                                                                            <a href="{{ Storage::url($quotation->attachment_file) }}" target="_blank" class="text-decoration-none">
                                                                                {{ basename($quotation->attachment_file) }}
                                                                            </a>
                                                                            <br>
                                                                            <small class="text-muted">Klik untuk melihat/download file</small>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <p class="text-muted mb-0">Belum ada file lampiran</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal for SAP input -->
                                <div class="modal fade" id="modal-{{ $quotation->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Input No SAP - {{ $quotation->nama_customer }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('sap.update-sap-number', $quotation) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">No SAP</label>
                                                        <input type="text" class="form-control" name="sap_number" value="{{ $quotation->sap_number }}" placeholder="Masukkan nomor SAP">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-select" name="status" required>
                                                            <option value="proses" {{ $quotation->status === 'proses' ? 'selected' : '' }}>Proses</option>
                                                            <option value="selesai" {{ $quotation->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Lampiran File Penawaran</label>
                                                        <input type="file" class="form-control" name="attachment_file" accept=".pdf,.doc,.docx,.xls,.xlsx">
                                                        <small class="form-text text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB.</small>
                                                        @if($quotation->attachment_file)
                                                            <div class="mt-2">
                                                                <small class="text-muted">File saat ini: <a href="{{ Storage::url($quotation->attachment_file) }}" target="_blank">{{ basename($quotation->attachment_file) }}</a></small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $quotations->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
