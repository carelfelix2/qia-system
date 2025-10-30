@extends('layouts.sales')

@section('title', 'Input Penawaran')

@section('content')
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
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jenis penawaran</label>
                                                <select name="jenis_penawaran" class="form-select" required>
                                                    <option value="">Pilih Jenis Penawaran</option>
                                                    <option value="Alat baru" {{ old('jenis_penawaran') == 'Alat baru' ? 'selected' : '' }}>Alat baru</option>
                                                    <option value="Re-kalibrasi" {{ old('jenis_penawaran') == 'Re-kalibrasi' ? 'selected' : '' }}>Re-kalibrasi</option>
                                                    <option value="Perbaikan" {{ old('jenis_penawaran') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                                    <option value="Sewa Alat" {{ old('jenis_penawaran') == 'Sewa Alat' ? 'selected' : '' }}>Sewa Alat</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Format Layout</label>
                                                <select name="format_layout" class="form-select" required>
                                                    <option value="">Pilih Format Layout</option>
                                                    <option value="With total" {{ old('format_layout') == 'With total' ? 'selected' : '' }}>With total</option>
                                                    <option value="Without total" {{ old('format_layout') == 'Without total' ? 'selected' : '' }}>Without total</option>
                                                </select>
                                            </div>
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
                                            <label class="form-label">Equipment Details</label>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <input type="text" id="equipment_search" class="form-control" placeholder="Search equipment...">
                                                    <div id="equipment_suggestions" class="dropdown-menu" style="display: none; position: absolute; z-index: 1000; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; background: white;"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" id="add_equipment_btn" class="btn btn-secondary w-100">Add Equipment</button>
                                                </div>
                                            </div>
                                            <div class="row" id="equipment_form" style="display: none;">
                                                <div class="col-md-2">
                                                    <input type="text" id="nama_alat_input" class="form-control" placeholder="Nama Alat">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" id="tipe_alat_input" class="form-control" placeholder="Tipe Alat">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" id="merk_input" class="form-control" placeholder="Merk">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" id="part_number_input" class="form-control" placeholder="Part Number Alat">
                                                </div>
                                                <div class="col-md-2">
                                                    <select id="kategori_harga_input" class="form-select">
                                                        <option value="">Kategori Harga</option>
                                                        <option value="harga_retail">Harga Retail</option>
                                                        <option value="harga_inaproc">Harga Inaproc</option>
                                                        <option value="harga_sebelum_ppn">Harga Sebelum PPN</option>
                                                        <option value="manual">Manual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" id="harga_input" class="form-control" placeholder="Harga" step="0.01" min="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="table-responsive draggable-table">
                                            <table class="table table-bordered" id="equipment_table">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Alat</th>
                                                        <th>Tipe Alat</th>
                                                        <th>Merk</th>
                                                        <th>Part Number</th>
                                                        <th>Kategori Harga</th>
                                                        <th>Harga</th>
                                                        <th>PPN</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="equipment_table_body">
                                                    <!-- Equipment rows will be added here -->
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <!-- Hidden inputs for form submission -->
                                        <div id="hidden_inputs_container"></div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Diskon (%)</label>
                                                <input type="number" name="diskon" class="form-control" value="{{ old('diskon') }}" min="0" max="100" step="0.01">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Pembayaran</label>
                                                <select name="pembayaran" class="form-select" id="pembayaran-select" required>
                                                    <option value="">Pilih Pembayaran</option>
                                                    <option value="30% DP, 70% Sisanya sebelum delivery" {{ old('pembayaran') == '30% DP, 70% Sisanya sebelum delivery' ? 'selected' : '' }}>30% DP, 70% Sisanya sebelum delivery</option>
                                                    <option value="100% Setelah barang diterima" {{ old('pembayaran') == '100% Setelah barang diterima' ? 'selected' : '' }}>100% Setelah barang diterima</option>
                                                    <option value="TT In Advance" {{ old('pembayaran') == 'TT In Advance' ? 'selected' : '' }}>TT In Advance</option>
                                                    <option value="Manual" {{ old('pembayaran') == 'Manual' ? 'selected' : '' }}>Isi Manual</option>
                                                </select>
                                                <input type="text" name="pembayaran_other" id="pembayaran-other" class="form-control mt-2" placeholder="Isi manual pembayaran" value="{{ old('pembayaran_other') }}" style="display: none;">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stok Barang / Lama Pengerjaan</label>
                                            <select name="stok" class="form-select" id="stok-select" required>
                                                <option value="">Pilih Stok</option>
                                                <option value="Ready stock tidak mengikat" {{ old('stok') == 'Ready stock tidak mengikat' ? 'selected' : '' }}>Ready stock tidak mengikat</option>
                                                <option value="Indent 10-12 Minggu (Setelah DP diterima)" {{ old('stok') == 'Indent 10-12 Minggu (Setelah DP diterima)' ? 'selected' : '' }}>Indent 10-12 Minggu (Setelah DP diterima)</option>
                                                <option value="Indent 10-12 Minggu" {{ old('stok') == 'Indent 10-12 Minggu' ? 'selected' : '' }}>Indent 10-12 Minggu</option>
                                                <option value="Manual" {{ old('stok') == 'Manual' ? 'selected' : '' }}>Isi Manual</option>
                                            </select>
                                            <input type="text" name="stok_other" id="stok-other" class="form-control mt-2" placeholder="Isi manual stok barang / lama pengerjaan" value="{{ old('stok_other') }}" style="display: none;">
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
@endsection

@section('scripts')
<script>
        let equipmentIndex = 0;

        // Equipment search functionality
        document.getElementById('equipment_search').addEventListener('input', function() {
            const query = this.value.trim();
            const suggestions = document.getElementById('equipment_suggestions');

            if (query.length < 2) {
                suggestions.style.display = 'none';
                return;
            }

            fetch(`/api/equipment/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const li = document.createElement('a');
                            li.className = 'dropdown-item';
                            li.href = '#';
                            li.textContent = `${item.nama_alat} - ${item.tipe_alat} - ${item.merk} (${item.part_number})`;
                            li.addEventListener('click', function(e) {
                                e.preventDefault();
                                window.currentEquipmentItem = item;
                                fillEquipmentForm(item);
                                suggestions.style.display = 'none';
                                document.getElementById('equipment_search').value = '';
                            });
                            suggestions.appendChild(li);
                        });
                        suggestions.style.display = 'block';
                    } else {
                        suggestions.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching equipment:', error);
                    suggestions.style.display = 'none';
                });
        });

        function fillEquipmentForm(item) {
            document.getElementById('nama_alat_input').value = item.nama_alat || '';
            document.getElementById('tipe_alat_input').value = item.tipe_alat || '';
            document.getElementById('merk_input').value = item.merk || '';
            document.getElementById('part_number_input').value = item.part_number || '';
            document.getElementById('kategori_harga_input').value = 'manual'; // Default to manual
            document.getElementById('harga_input').value = '';
            document.getElementById('harga_input').readOnly = false;
            document.getElementById('equipment_form').style.display = 'flex';
        }

        // Add event listener for kategori_harga change
        document.getElementById('kategori_harga_input').addEventListener('change', function() {
            const selected = this.value;
            const hargaInput = document.getElementById('harga_input');
            if (selected === 'manual') {
                hargaInput.readOnly = false;
                hargaInput.value = '';
            } else {
                // Fetch price from item data if available, else allow manual
                const item = window.currentEquipmentItem;
                if (item && item[selected]) {
                    hargaInput.value = item[selected];
                    hargaInput.readOnly = true;
                } else {
                    hargaInput.readOnly = false;
                    hargaInput.value = '';
                }
            }
        });

        document.getElementById('add_equipment_btn').addEventListener('click', function() {
            const namaAlat = document.getElementById('nama_alat_input').value.trim();
            const tipeAlat = document.getElementById('tipe_alat_input').value.trim();
            const merk = document.getElementById('merk_input').value.trim();
            const partNumber = document.getElementById('part_number_input').value.trim();
            const kategoriHarga = document.getElementById('kategori_harga_input').value;
            const harga = document.getElementById('harga_input').value.trim();

            if (!namaAlat || !tipeAlat || !partNumber || !kategoriHarga || !harga) {
                alert('Please fill in all required fields.');
                return;
            }

            // Add row to table
            const tableBody = document.getElementById('equipment_table_body');
            const row = tableBody.insertRow();
            row.innerHTML = `
                <td>${namaAlat}</td>
                <td>${tipeAlat}</td>
                <td>${merk}</td>
                <td>${partNumber}</td>
                <td>${kategoriHarga}</td>
                <td><input type="number" class="form-control" name="items[${equipmentIndex}][harga]" value="${harga}" step="0.01" min="0" required></td>
                <td>
                    <select class="form-select" name="items[${equipmentIndex}][ppn]" required>
                        <option value="">Pilih</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm remove-equipment">Remove</button></td>
            `;

            // Add hidden inputs for the filled fields
            const hiddenContainer = document.getElementById('hidden_inputs_container');
            hiddenContainer.innerHTML += `
                <input type="hidden" name="items[${equipmentIndex}][nama_alat]" value="${namaAlat}">
                <input type="hidden" name="items[${equipmentIndex}][tipe_alat]" value="${tipeAlat}">
                <input type="hidden" name="items[${equipmentIndex}][merk]" value="${merk}">
                <input type="hidden" name="items[${equipmentIndex}][part_number]" value="${partNumber}">
                <input type="hidden" name="items[${equipmentIndex}][kategori_harga]" value="${kategoriHarga}">
                <input type="hidden" name="items[${equipmentIndex}][harga]" value="${harga}">
            `;

            // Clear input fields
            document.getElementById('nama_alat_input').value = '';
            document.getElementById('tipe_alat_input').value = '';
            document.getElementById('merk_input').value = '';
            document.getElementById('part_number_input').value = '';
            document.getElementById('kategori_harga_input').value = '';
            document.getElementById('harga_input').value = '';
            document.getElementById('equipment_form').style.display = 'none';

            equipmentIndex++;
        });

        // Event delegation for remove buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-equipment')) {
                const row = e.target.closest('tr');
                const index = Array.from(row.parentNode.children).indexOf(row);

                // Remove row from table
                row.remove();

                // Remove corresponding hidden inputs
                const hiddenContainer = document.getElementById('hidden_inputs_container');
                const hiddenInputs = hiddenContainer.querySelectorAll(`input[name^="items[${index}]"]`);
                hiddenInputs.forEach(input => input.remove());

                // Re-index remaining items
                reindexEquipment();
            }
        });

        function reindexEquipment() {
            const rows = document.querySelectorAll('#equipment_table_body tr');
            const hiddenContainer = document.getElementById('hidden_inputs_container');
            hiddenContainer.innerHTML = '';

            rows.forEach((row, newIndex) => {
                const cells = row.querySelectorAll('td');
                const inputs = row.querySelectorAll('input, select');

                // Update input names
                inputs.forEach(input => {
                    const name = input.name.replace(/\[\d+\]/, `[${newIndex}]`);
                    input.name = name;
                });

                // Re-add hidden inputs
                const namaAlat = cells[0].textContent;
                const tipeAlat = cells[1].textContent;
                const merk = cells[2].textContent;
                const partNumber = cells[3].textContent;
                const kategoriHarga = cells[4].textContent;
                const harga = cells[5].querySelector('input').value;

                hiddenContainer.innerHTML += `
                    <input type="hidden" name="items[${newIndex}][nama_alat]" value="${namaAlat}">
                    <input type="hidden" name="items[${newIndex}][tipe_alat]" value="${tipeAlat}">
                    <input type="hidden" name="items[${newIndex}][merk]" value="${merk}">
                    <input type="hidden" name="items[${newIndex}][part_number]" value="${partNumber}">
                    <input type="hidden" name="items[${newIndex}][kategori_harga]" value="${kategoriHarga}">
                    <input type="hidden" name="items[${newIndex}][harga]" value="${harga}">
                `;
            });

            equipmentIndex = rows.length;
        }

        document.getElementById('pembayaran-select').addEventListener('change', function() {
            const otherInput = document.getElementById('pembayaran-other');
            if (this.value === 'Manual') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
            }
        });

        document.getElementById('stok-select').addEventListener('change', function() {
            const otherInput = document.getElementById('stok-other');
            if (this.value === 'Manual') {
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

            if (pembayaranSelect.value === 'Manual') {
                document.getElementById('pembayaran-other').style.display = 'block';
            }
            if (stokSelect.value === 'Manual') {
                document.getElementById('stok-other').style.display = 'block';
            }
        });

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
    </script>

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
@endsection
