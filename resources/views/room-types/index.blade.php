@extends('layouts.app')
@section('title', 'Tipe Kamar')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin:0;border:none">Daftar Tipe Kamar</div>
        <a href="/room-types/create" class="btn btn-gold">+ Tambah Tipe</a>
    </div>
    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kapasitas</th>
                <th>Harga Weekday</th>
                <th>Harga Weekend</th>
                <th>Jumlah Kamar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roomTypes as $type)
            <tr>
                <td><strong>{{ $type->name }}</strong></td>
                <td>{{ $type->capacity }} orang</td>

                {{-- Harga Weekday inline edit --}}
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span id="price-{{ $type->id }}"
                            ondblclick="editPrice({{ $type->id }}, 'weekday')"
                            style="cursor:pointer;border-bottom:1px dashed #d4af7a;padding-bottom:1px"
                            title="Double klik untuk edit">
                            Rp {{ number_format($type->base_price, 0, ',', '.') }}
                        </span>
                        <i class="fas fa-pen" style="font-size:10px;color:#d4af7a;cursor:pointer;opacity:0.6"
                            onclick="editPrice({{ $type->id }}, 'weekday')" title="Edit harga"></i>
                    </div>
                    <input type="number" id="input-price-{{ $type->id }}"
                        style="display:none;width:130px;padding:6px 10px;border:1px solid #d4af7a;border-radius:4px;font-size:14px;font-family:Arial"
                        onblur="savePrice({{ $type->id }}, 'base_price')"
                        onkeydown="if(event.key==='Enter') savePrice({{ $type->id }}, 'base_price'); if(event.key==='Escape') cancelEdit({{ $type->id }})">
                </td>

                {{-- Harga Weekend inline edit --}}
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span id="weekend-{{ $type->id }}"
                            ondblclick="editPrice({{ $type->id }}, 'weekend')"
                            style="cursor:pointer;border-bottom:1px dashed #d4af7a;padding-bottom:1px"
                            title="Double klik untuk edit">
                            Rp {{ number_format($type->weekend_price ?? $type->base_price, 0, ',', '.') }}
                        </span>
                        <i class="fas fa-pen" style="font-size:10px;color:#d4af7a;cursor:pointer;opacity:0.6"
                            onclick="editPrice({{ $type->id }}, 'weekend')" title="Edit harga"></i>
                    </div>
                    <input type="number" id="input-weekend-{{ $type->id }}"
                        style="display:none;width:130px;padding:6px 10px;border:1px solid #d4af7a;border-radius:4px;font-size:14px;font-family:Arial"
                        onblur="savePrice({{ $type->id }}, 'weekend_price')"
                        onkeydown="if(event.key==='Enter') savePrice({{ $type->id }}, 'weekend_price'); if(event.key==='Escape') cancelEdit({{ $type->id }})">
                </td>

                <td>{{ $type->rooms_count }} kamar</td>
                <td style="display:flex;gap:8px">
                    <a href="/room-types/{{ $type->id }}/edit" class="btn btn-outline" style="padding:5px 12px;font-size:12px">Edit</a>
                    <form method="POST" action="/room-types/{{ $type->id }}" onsubmit="return confirm('Hapus tipe kamar ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" style="padding:5px 12px;font-size:12px">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:32px">Belum ada tipe kamar</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div style="margin-top:12px;font-size:12px;color:#aaa;font-family:Arial">
        <i class="fas fa-info-circle" style="color:#d4af7a"></i>
        Klik ikon pensil atau double klik harga untuk edit langsung.
        Tekan <strong>Enter</strong> untuk simpan, <strong>Esc</strong> untuk batal.
    </div>
</div>

@push('scripts')
<script>
function editPrice(id, type) {
    const isWeekend = type === 'weekend';
    const spanId    = isWeekend ? `weekend-${id}` : `price-${id}`;
    const inputId   = isWeekend ? `input-weekend-${id}` : `input-price-${id}`;

    const span  = document.getElementById(spanId);
    const input = document.getElementById(inputId);

    // Ambil nilai angka dari teks
    const currentText = span.textContent.replace(/[^0-9]/g, '');
    input.value = currentText;

    span.closest('div').style.display = 'none';
    input.style.display = 'block';
    input.focus();
    input.select();
}

async function savePrice(id, field) {
    const isWeekend = field === 'weekend_price';
    const inputId   = isWeekend ? `input-weekend-${id}` : `input-price-${id}`;
    const spanId    = isWeekend ? `weekend-${id}` : `price-${id}`;
    const input     = document.getElementById(inputId);
    const span      = document.getElementById(spanId);
    const value     = input.value;

    if (!value || isNaN(value) || value < 0) {
        alert('Harga tidak valid!');
        return;
    }

    try {
        const res = await fetch(`/room-types/${id}/update-price`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ field, value: parseFloat(value) })
        });

        const data = await res.json();

        if (data.success) {
            // Update tampilan
            span.textContent = 'Rp ' + parseFloat(value).toLocaleString('id-ID');
            input.style.display = 'none';
            span.closest('div').style.display = 'flex';

            // Flash hijau
            span.style.color = '#2e7d32';
            setTimeout(() => span.style.color = '', 1500);
        } else {
            alert('Gagal menyimpan: ' + (data.message || 'Error'));
        }
    } catch(e) {
        alert('Error: ' + e.message);
    }
}

function cancelEdit(id) {
    ['price', 'weekend'].forEach(type => {
        const input = document.getElementById(`input-${type}-${id}`);
        const wrap  = document.getElementById(`${type}-${id}`)?.closest('div');
        if (input) input.style.display = 'none';
        if (wrap)  wrap.style.display  = 'flex';
    });
}
</script>
@endpush
@endsection