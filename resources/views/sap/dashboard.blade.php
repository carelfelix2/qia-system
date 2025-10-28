@extends('layouts.sap')

@section('title', 'Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dashboard {{ auth()->user()->hasRole('inputer_spk') ? 'Inputer SPK' : 'Inputer SAP' }}</h3>
            </div>
            <div class="card-body">
                <p>Selamat datang di dashboard {{ auth()->user()->hasRole('inputer_spk') ? 'Inputer SPK' : 'Inputer SAP' }}. Anda dapat mengelola penawaran dari sales dan menginput nomor SAP.</p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="h1 mb-3">{{ \App\Models\Quotation::count() }}</div>
                                <div class="text-muted">Total Penawaran</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="h1 mb-3">{{ \App\Models\Quotation::where('status', 'selesai')->count() }}</div>
                                <div class="text-muted">Penawaran Selesai</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('sap.daftar-penawaran') }}" class="btn btn-primary">
                        Lihat Daftar Penawaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
