@extends('layouts.sap')
@section('title', 'Daftar Penawaran - SAP Dashboard')

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
                    <div class="table-responsive draggable-table">
                        <table class="table table-vcenter table-hover">
                            <thead>
                                                <tr>
                                    <th>
                                        Tanggal
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none ms-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <polyline points="6 15 12 9 18 15" />
                                            </svg>
                                        </a>
                                    </th>
                                    <th>No SAP</th>
                                    <th>Nama Sales</th>
                                    <th>Nama Customer</th>
                                    <th>Status</th>
                                    <th>Detail Penawaran</th>
                                    <th>Input SQ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotations as $index => $quotation)
                                <tr>
                                    <td>{{ $quotation->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($quotation->sap_number)
                                            {{ $quotation->sap_number }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $quotation->sales_person }}</td>
                                    <td>{{ $quotation->nama_customer }}</td>
                                    <td>
                                        <span class="badge {{ $quotation->status === 'selesai' ? 'bg-success' : 'bg-warning' }}  text-white">
                                            {{ ucfirst($quotation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $quotation->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="2" />
                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                            </svg>
                                            Detail
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-{{ $quotation->id }}">
                                            Input SQ
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Detail Penawaran Modal -->
                    @foreach($quotations as $quotation)
                    <div class="modal fade" id="detailModal-{{ $quotation->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $quotation->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel-{{ $quotation->id }}">Detail Penawaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Nama Customer:</strong> {{ $quotation->nama_customer }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Jenis Penawaran:</strong> {{ $quotation->jenis_penawaran }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Format Layout:</strong> {{ $quotation->format_layout }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Status:</strong>
                                            @if($quotation->status === 'selesai')
                                                <span class="badge bg-success text-white">Selesai</span>
                                            @else
                                                <span class="badge bg-warning text-white">Proses</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Pembayaran:</strong>
                                            @if($quotation->pembayaran === 'Manual')
                                                {{ $quotation->pembayaran_other }}
                                            @else
                                                {{ $quotation->pembayaran }}
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Stok Barang:</strong>
                                            @if($quotation->stok === 'Manual')
                                                {{ $quotation->stok_other }}
                                            @else
                                                {{ $quotation->stok }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Diskon:</strong> {{ $quotation->diskon ? $quotation->diskon . '%' : '-' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Alamat Customer:</strong> {{ $quotation->alamat_customer }}
                                        </div>
                                    </div>

                                    @if($quotation->quotationItems->count() > 0)
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
                                    @else
                                        <p class="text-muted">Tidak ada alat dalam penawaran ini.</p>
                                    @endif

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

                                    @if($quotation->poFiles->count() > 0)
                                        <h6>File PO:</h6>
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
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Modal for SAP input -->
                    @foreach($quotations as $quotation)
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

                    <!-- Pagination -->
                    <div class="card-footer">
                        <div class="row g-2 justify-content-center justify-content-sm-between">
                            <div class="col-auto d-flex align-items-center">
                                <p class="m-0 text-secondary">Showing <strong>{{ $quotations->firstItem() ?? 0 }} to {{ $quotations->lastItem() ?? 0 }}</strong> of <strong>{{ $quotations->total() }} entries</strong></p>
                            </div>
                            <div class="col-auto">
                                {{ $quotations->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
