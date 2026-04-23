@extends('layouts.app')

@section('title', 'Tambah Konten')
@section('breadcrumb', 'Tambah Konten')

@section('content')

<div class="form-page">
    <div class="form-header">
        <a href="{{ route('konten.index') }}" class="back-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali
        </a>
        <div>
            <h1 class="form-title">Tambah Konten Baru</h1>
            <p class="form-subtitle">Isi semua field di bawah untuk membuat konten baru.</p>
        </div>
    </div>

    <form action="{{ route('konten.store') }}" method="POST" class="content-form" id="contentForm">
        @csrf

        <div class="form-grid">

            {{-- ── Judul ── --}}
            <div class="form-group form-group-full">
                <label class="form-label" for="judul">
                    Judul Konten
                    <span class="required-star">*</span>
                </label>
                <input
                    type="text"
                    id="judul"
                    name="judul"
                    class="form-control @error('judul') is-error @enderror"
                    placeholder="Masukkan judul konten..."
                    value="{{ old('judul') }}"
                    autofocus
                >
                @error('judul')
                    <span class="form-error">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- ── Pembuat ── --}}
            <div class="form-group">
                <label class="form-label" for="pembuat">
                    Nama Pembuat
                    <span class="required-star">*</span>
                </label>
                <input
                    type="text"
                    id="pembuat"
                    name="pembuat"
                    class="form-control @error('pembuat') is-error @enderror"
                    placeholder="Nama pembuat konten..."
                    value="{{ old('pembuat') }}"
                >
                @error('pembuat')
                    <span class="form-error">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- ── Status ── --}}
            <div class="form-group">
                <label class="form-label" for="status">
                    Status
                    <span class="required-star">*</span>
                </label>
                <div class="select-wrap">
                    <select id="status" name="status" class="form-control form-select @error('status') is-error @enderror" onchange="handleStatusChange(this)">
                        <option value="draft"     {{ old('status', 'draft') == 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    <svg class="select-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                @error('status')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- ── Tanggal Publikasi ── --}}
            <div class="form-group" id="tanggalGroup">
                <label class="form-label" for="tanggal_publikasi">
                    Tanggal Publikasi
                    <span class="form-hint">(opsional jika Draft)</span>
                </label>
                <input
                    type="datetime-local"
                    id="tanggal_publikasi"
                    name="tanggal_publikasi"
                    class="form-control @error('tanggal_publikasi') is-error @enderror"
                    value="{{ old('tanggal_publikasi') }}"
                >
                @error('tanggal_publikasi')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- ── Isi Konten ── --}}
            <div class="form-group form-group-full">
                <label class="form-label" for="isi">
                    Isi Konten
                    <span class="required-star">*</span>
                </label>
                <div class="textarea-wrap">
                    <textarea
                        id="isi"
                        name="isi"
                        class="form-control form-textarea @error('isi') is-error @enderror"
                        placeholder="Tulis isi konten di sini..."
                        rows="10"
                    >{{ old('isi') }}</textarea>
                    <div class="char-counter"><span id="charCount">0</span> karakter</div>
                </div>
                @error('isi')
                    <span class="form-error">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>

        </div>

        {{-- ── Form Actions ── --}}
        <div class="form-actions">
            <a href="{{ route('konten.index') }}" class="btn btn-ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Batal
            </a>
            <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Konten
            </button>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
    // Character counter
    const isiField = document.getElementById('isi');
    const charCount = document.getElementById('charCount');
    function updateCount() {
        charCount.textContent = isiField.value.length.toLocaleString('id-ID');
    }
    isiField.addEventListener('input', updateCount);
    updateCount();

    // Show/hide tanggal based on status
    function handleStatusChange(select) {
        const group = document.getElementById('tanggalGroup');
        const tanggalInput = document.getElementById('tanggal_publikasi');
        if (select.value === 'published') {
            group.classList.add('field-required');
            if (!tanggalInput.value) {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                tanggalInput.value = now.toISOString().slice(0,16);
            }
        } else {
            group.classList.remove('field-required');
        }
    }
    // Init on load
    handleStatusChange(document.getElementById('status'));
</script>
@endpush