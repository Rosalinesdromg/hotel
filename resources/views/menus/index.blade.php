@extends('layouts.app')
@section('title', 'Menu Restoran')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin:0;border:none">Menu Restoran</div>
        <a href="/menus/create" class="btn btn-gold">+ Tambah Menu</a>
    </div>
    <div class="table-responsive">
    <table id="menus-table">
        <thead>
            <tr>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr>
                <td><strong>{{ $menu->name }}</strong></td>
                <td>{{ $menu->category }}</td>

                {{-- Harga inline edit --}}
                <td>
                    <div style="display:flex;align-items:center;gap:8px" id="price-wrap-{{ $menu->id }}">
                        <span id="menu-price-{{ $menu->id }}"
                            style="cursor:pointer;border-bottom:1px dashed #d4af7a;padding-bottom:1px"
                            title="Double klik untuk edit"
                            ondblclick="editMenuPrice({{ $menu->id }})">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </span>
                        <i class="fas fa-pen" style="font-size:10px;color:#d4af7a;cursor:pointer;opacity:0.6"
                            onclick="editMenuPrice({{ $menu->id }})"></i>
                    </div>
                    <input type="number" id="menu-input-{{ $menu->id }}"
                        style="display:none;width:130px;padding:6px 10px;border:1px solid #d4af7a;border-radius:4px;font-size:14px;font-family:Arial"
                        onblur="saveMenuPrice({{ $menu->id }})"
                        onkeydown="if(event.key==='Enter') saveMenuPrice({{ $menu->id }}); if(event.key==='Escape') cancelMenuEdit({{ $menu->id }})">
                </td>

                {{-- Stok inline edit --}}
                <td>
                    <div style="display:flex;align-items:center;gap:8px" id="stock-wrap-{{ $menu->id }}">
                        <span id="menu-stock-{{ $menu->id }}"
                            style="cursor:pointer;border-bottom:1px dashed #aaa;padding-bottom:1px"
                            title="Double klik untuk edit"
                            ondblclick="editMenuStock({{ $menu->id }})">
                            {{ $menu->stock }}
                        </span>
                        <i class="fas fa-pen" style="font-size:10px;color:#aaa;cursor:pointer;opacity:0.6"
                            onclick="editMenuStock({{ $menu->id }})"></i>
                    </div>
                    <input type="number" id="stock-input-{{ $menu->id }}"
                        style="display:none;width:90px;padding:6px 10px;border:1px solid #d4af7a;border-radius:4px;font-size:14px;font-family:Arial"
                        onblur="saveMenuStock({{ $menu->id }})"
                        onkeydown="if(event.key==='Enter') saveMenuStock({{ $menu->id }}); if(event.key==='Escape') cancelMenuEdit({{ $menu->id }})">
                </td>

                <td>
                    @if($menu->is_available)
                        <span class="badge badge-available">Tersedia</span>
                    @else
                        <span class="badge badge-pending">Out of Stock</span>
                    @endif
                </td>
                <td style="display:flex;gap:8px">
                    <a href="/menus/{{ $menu->id }}/edit" class="btn btn-outline" style="padding:5px 12px;font-size:12px">Edit</a>
                    <form method="POST" action="/menus/{{ $menu->id }}" onsubmit="return confirm('Hapus menu ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" style="padding:5px 12px;font-size:12px">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:32px">Belum ada menu</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div style="margin-top:12px;font-size:12px;color:#aaa;font-family:Arial">
        <i class="fas fa-info-circle" style="color:#d4af7a"></i>
        Klik ikon pensil atau double klik untuk edit harga/stok langsung.
        Tekan <strong>Enter</strong> untuk simpan, <strong>Esc</strong> untuk batal.
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#menus-table').DataTable({
        responsive: true,
        language: {
            search: "Cari:", lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Data tidak ditemukan",
            paginate: { next: "→", previous: "←" }
        },
        columnDefs: [{ orderable: false, targets: -1 }]
    });
});

function editMenuPrice(id) {
    const span  = document.getElementById(`menu-price-${id}`);
    const input = document.getElementById(`menu-input-${id}`);
    const wrap  = document.getElementById(`price-wrap-${id}`);
    input.value = span.textContent.replace(/[^0-9]/g, '');
    wrap.style.display  = 'none';
    input.style.display = 'block';
    input.focus(); input.select();
}

function editMenuStock(id) {
    const span  = document.getElementById(`menu-stock-${id}`);
    const input = document.getElementById(`stock-input-${id}`);
    const wrap  = document.getElementById(`stock-wrap-${id}`);
    input.value = span.textContent.trim();
    wrap.style.display  = 'none';
    input.style.display = 'block';
    input.focus(); input.select();
}

async function saveMenuPrice(id) {
    const input = document.getElementById(`menu-input-${id}`);
    const span  = document.getElementById(`menu-price-${id}`);
    const wrap  = document.getElementById(`price-wrap-${id}`);
    const value = input.value;
    if (!value || isNaN(value) || value < 0) { alert('Harga tidak valid!'); return; }

    const res  = await fetch(`/menus/${id}/update-field`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ field: 'price', value: parseFloat(value) })
    });
    const data = await res.json();
    if (data.success) {
        span.textContent    = 'Rp ' + parseFloat(value).toLocaleString('id-ID');
        input.style.display = 'none';
        wrap.style.display  = 'flex';
        span.style.color    = '#2e7d32';
        setTimeout(() => span.style.color = '', 1500);
    }
}

async function saveMenuStock(id) {
    const input = document.getElementById(`stock-input-${id}`);
    const span  = document.getElementById(`menu-stock-${id}`);
    const wrap  = document.getElementById(`stock-wrap-${id}`);
    const value = input.value;
    if (value === '' || isNaN(value) || value < 0) { alert('Stok tidak valid!'); return; }

    const res  = await fetch(`/menus/${id}/update-field`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ field: 'stock', value: parseInt(value) })
    });
    const data = await res.json();
    if (data.success) {
        span.textContent    = value;
        input.style.display = 'none';
        wrap.style.display  = 'flex';
        span.style.color    = '#2e7d32';
        setTimeout(() => span.style.color = '', 1500);
    }
}

function cancelMenuEdit(id) {
    ['menu-input', 'stock-input'].forEach(prefix => {
        const input = document.getElementById(`${prefix}-${id}`);
        if (input) input.style.display = 'none';
    });
    ['price-wrap', 'stock-wrap'].forEach(prefix => {
        const wrap = document.getElementById(`${prefix}-${id}`);
        if (wrap) wrap.style.display = 'flex';
    });
}
</script>
@endpush
@endsection