@extends('layouts.app')
@section('title', 'Tulis Review')

@section('content')
<div style="max-width:560px">
    <div class="card">
        <div class="card-title">Tulis Review</div>

        <div style="background:#f9f7f4;border-radius:8px;padding:16px;margin-bottom:24px;font-family:Arial">
            <div style="font-size:14px;color:#1a1a2e;margin-bottom:4px">
                {{ $booking->room->roomType->name }} — Kamar {{ $booking->room->room_number }}
            </div>
            <div style="font-size:13px;color:#aaa">
                {{ $booking->check_in->format('d M Y') }} → {{ $booking->check_out->format('d M Y') }}
            </div>
        </div>

        <form method="POST" action="/reviews/{{ $booking->id }}">
            @csrf

            {{-- Rating bintang --}}
            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:12px;font-family:Arial">Rating</label>
                <div class="star-rating" style="display:flex;gap:8px">
                    @for($i = 5; $i >= 1; $i--)
                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                        style="display:none" {{ old('rating') == $i ? 'checked' : '' }}>
                    <label for="star{{ $i }}"
                        style="font-size:36px;color:#ddd;cursor:pointer;transition:color 0.2s"
                        onmouseover="highlightStars({{ $i }})"
                        onmouseout="resetStars()"
                        onclick="selectStar({{ $i }})">★</label>
                    @endfor
                </div>
                @error('rating')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;color:#666;margin-bottom:8px;font-family:Arial">
                    Komentar <span style="color:#aaa">(min. 10 karakter)</span>
                </label>
                <textarea name="comment" rows="5"
                    style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:Arial;resize:vertical"
                    placeholder="Ceritakan pengalaman menginap kamu...">{{ old('comment') }}</textarea>
                @error('comment')<span style="color:red;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-gold">Kirim Review</button>
                <a href="/my-bookings" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let selected = {{ old('rating', 0) }};
const labels = document.querySelectorAll('.star-rating label');

// Balik urutan karena kita render dari 5 ke 1
function highlightStars(n) {
    labels.forEach((l, i) => {
        // index 0 = bintang 5, index 4 = bintang 1
        l.style.color = (5 - i) <= n ? '#d4af7a' : '#ddd';
    });
}

function resetStars() {
    labels.forEach((l, i) => {
        l.style.color = (5 - i) <= selected ? '#d4af7a' : '#ddd';
    });
}

function selectStar(n) {
    selected = n;
    document.getElementById('star' + n).checked = true;
    resetStars();
}

// Init kalau ada old value
if (selected > 0) highlightStars(selected);
</script>
@endpush
@endsection