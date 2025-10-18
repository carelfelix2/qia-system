<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penawaran - Sales Dashboard</title>
    @vite('resources/css/tabler.css')
</head>
<body>
    <div class="page">
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href=".">
                        <img src="{{ asset('images/logo.png') }}" width="110" height="32" alt="Logo" class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm" style="background-image: url(https://preview.tabler.io/static/avatars/000m.jpg)"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ auth()->user()->name }}</div>
                                <div class="mt-1 small text-muted">Sales</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">Profile</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container-xl">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('sales.dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                    </span>
                                    <span class="nav-link-title">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('sales.input-penawaran.create') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                                    </span>
                                    <span class="nav-link-title">Input Penawaran</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('sales.daftar-penawaran') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2" /><line x1="4" y1="10" x2="20" y2="10" /><line x1="10" y1="4" x2="10" y2="20" /></svg>
                                    </span>
                                    <span class="nav-link-title">Daftar Penawaran</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Input Penawaran</h3>
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('sales.input-penawaran.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Sales</label>
                                            <input type="text" name="sales_person" class="form-control" value="{{ auth()->user()->name }}" readonly required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jenis penawaran</label>
                                            <select name="jenis_penawaran" class="form-select" required>
                                                <option value="">Pilih Jenis Penawaran</option>
                                                <option value="Alat baru" {{ old('jenis_penawaran') == 'Alat baru' ? 'selected' : '' }}>Alat baru</option>
                                                <option value="Re-kalibrasi" {{ old('jenis_penawaran') == 'Re-kalibrasi' ? 'selected' : '' }}>Re-kalibrasi</option>
                                                <option value="Perbaikan" {{ old('jenis_penawaran') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                                <option value="Sewa Alat" {{ old('jenis_penawaran') == 'Sewa Alat' ? 'selected' : '' }}>Sewa Alat</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Format Layout</label>
                                            <select name="format_layout" class="form-select" required>
                                                <option value="">Pilih Format Layout</option>
                                                <option value="With total" {{ old('format_layout') == 'With total' ? 'selected' : '' }}>With total</option>
                                                <option value="Without total" {{ old('format_layout') == 'Without total' ? 'selected' : '' }}>Without total</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Customer</label>
                                            <input type="text" name="nama_customer" class="form-control" value="{{ old('nama_customer') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat Customer</label>
                                            <textarea name="alamat_customer" class="form-control" rows="3" required>{{ old('alamat_customer') }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Alat, Tipe Alat dan Merk</label>
                                            <input type="text" name="nama_alat" class="form-control" value="{{ old('nama_alat') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Part Number Alat</label>
                                            <input type="text" name="part_number" class="form-control" value="{{ old('part_number') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">KATEGORI HARGA</label>
                                            <select name="kategori_harga" class="form-select" required>
                                                <option value="">Pilih Kategori Harga</option>
                                                <option value="HARGA INAPROC 2025" {{ old('kategori_harga') == 'HARGA INAPROC 2025' ? 'selected' : '' }}>HARGA INAPROC 2025</option>
                                                <option value="HARGA RETAIL 2025" {{ old('kategori_harga') == 'HARGA RETAIL 2025' ? 'selected' : '' }}>HARGA RETAIL 2025</option>
                                                <option value="NON E-KATALOG (CUSTOM)" {{ old('kategori_harga') == 'NON E-KATALOG (CUSTOM)' ? 'selected' : '' }}>NON E-KATALOG (CUSTOM)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Harga</label>
                                            <input type="number" name="harga" class="form-control" step="0.01" min="0" value="{{ old('harga') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Sudah PPN?</label>
                                            <select name="ppn" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="YA" {{ old('ppn') == 'YA' ? 'selected' : '' }}>YA</option>
                                                <option value="Tidak" {{ old('ppn') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Diskon (Optional)</label>
                                            <input type="number" name="diskon" class="form-control" step="0.01" min="0" max="100" value="{{ old('diskon') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Pembayaran</label>
                                            <select name="pembayaran" class="form-select" id="pembayaran-select" required>
                                                <option value="">Pilih Pembayaran</option>
                                                <option value="Keterangan pembayaran, 100%, 30% DP, atau TT in advance" {{ old('pembayaran') == 'Keterangan pembayaran, 100%, 30% DP, atau TT in advance' ? 'selected' : '' }}>Keterangan pembayaran, 100%, 30% DP, atau TT in advance</option>
                                                <option value="30% DP, 70% Sisanya sebelum delivery" {{ old('pembayaran') == '30% DP, 70% Sisanya sebelum delivery' ? 'selected' : '' }}>30% DP, 70% Sisanya sebelum delivery</option>
                                                <option value="100% Setelah barang diterima" {{ old('pembayaran') == '100% Setelah barang diterima' ? 'selected' : '' }}>100% Setelah barang diterima</option>
                                                <option value="TT In Advance" {{ old('pembayaran') == 'TT In Advance' ? 'selected' : '' }}>TT In Advance</option>
                                                <option value="Other:" {{ old('pembayaran') == 'Other:' ? 'selected' : '' }}>Other:</option>
                                            </select>
                                            <input type="text" name="pembayaran_other" id="pembayaran-other" class="form-control mt-2" placeholder="Specify other payment" value="{{ old('pembayaran_other') }}" style="display: none;">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stok Barang / Lama Pengerjaan</label>
                                            <select name="stok" class="form-select" id="stok-select" required>
                                                <option value="">Pilih Stok</option>
                                                <option value="Ready Stok / Indent, jika indent berapa lama? jika kalibrasi pengerjaan berapa lama?" {{ old('stok') == 'Ready Stok / Indent, jika indent berapa lama? jika kalibrasi pengerjaan berapa lama?' ? 'selected' : '' }}>Ready Stok / Indent, jika indent berapa lama? jika kalibrasi pengerjaan berapa lama?</option>
                                                <option value="Ready stock tidak mengikat" {{ old('stok') == 'Ready stock tidak mengikat' ? 'selected' : '' }}>Ready stock tidak mengikat</option>
                                                <option value="Indent 10-12 Minggu (Setelah DP diterima)" {{ old('stok') == 'Indent 10-12 Minggu (Setelah DP diterima)' ? 'selected' : '' }}>Indent 10-12 Minggu (Setelah DP diterima)</option>
                                                <option value="Indent 10-12 Minggu" {{ old('stok') == 'Indent 10-12 Minggu' ? 'selected' : '' }}>Indent 10-12 Minggu</option>
                                                <option value="Other:" {{ old('stok') == 'Other:' ? 'selected' : '' }}>Other:</option>
                                            </select>
                                            <input type="text" name="stok_other" id="stok-other" class="form-control mt-2" placeholder="Specify other stock info" value="{{ old('stok_other') }}" style="display: none;">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan Tambahan</label>
                                            <textarea name="keterangan_tambahan" class="form-control" rows="3">{{ old('keterangan_tambahan') }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Penawaran</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-lg-auto ms-lg-auto">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">© 2025 PT. Quantum Inti Akurasi - Made with ❤</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <script>
        document.getElementById('pembayaran-select').addEventListener('change', function() {
            const otherInput = document.getElementById('pembayaran-other');
            if (this.value === 'Other:') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
            }
        });

        document.getElementById('stok-select').addEventListener('change', function() {
            const otherInput = document.getElementById('stok-other');
            if (this.value === 'Other:') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
            }
        });

        // Initialize on page load in case of validation errors
        window.addEventListener('DOMContentLoaded', function() {
            const pembayaranSelect = document.getElementById('pembayaran-select');
            const stokSelect = document.getElementById('stok-select');

            if (pembayaranSelect.value === 'Other:') {
                document.getElementById('pembayaran-other').style.display = 'block';
            }
            if (stokSelect.value === 'Other:') {
                document.getElementById('stok-other').style.display = 'block';
            }
        });
    </script>
</body>
</html>
