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
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $quotation->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>{{ $quotation->sales_person }}</td>
                                                        <td>{{ $quotation->jenis_penawaran }}</td>
                                                        <td>{{ $quotation->nama_customer }}</td>
                                                        <td>{{ $quotation->quotationItems->first()?->diskon ?? '-' }}</td>
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
                                                                <span class="badge bg-success">Selesai</span>
                                                            @else
                                                                <span class="badge bg-warning">Proses</span>
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
                                                        <td colspan="11" class="p-0">
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
                                                                                    <td>{{ $item->diskon }}</td>
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
@endsection
