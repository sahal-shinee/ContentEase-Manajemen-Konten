@extends('layouts.app')

@section('title', 'Daftar Konten')
@section('breadcrumb', 'Daftar Konten')

@section('content')

{{-- ── STATS CARDS ── --}}
<div class="stats-grid">
    <div class="stat-card stat-total">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="stat-body">
            <span class="stat-label">Total Konten</span>
            <span class="stat-value">{{ $stats['total'] }}</span>
        </div>
    </div>
    <div class="stat-card stat-published">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="stat-body">
            <span class="stat-label">Published</span>
            <span class="stat-value">{{ $stats['published'] }}</span>
        </div>
    </div>
    <div class="stat-card stat-draft">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </div>
        <div class="stat-body">
            <span class="stat-label">Draft</span>
            <span class="stat-value">{{ $stats['draft'] }}</span>
        </div>
    </div>
</div>

{{-- ── TABLE SECTION ── --}}
<div class="table-section">
    <div class="table-header">
        <div class="table-header-left">
            <h2 class="table-title">Semua Konten</h2>
            <span class="table-count">{{ $konten->total() }} konten ditemukan</span>
        </div>
        <a href="{{ route('konten.create') }}" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Konten
        </a>
    </div>

    {{-- ── SEARCH & FILTER BAR ── --}}
    <form method="GET" action="{{ route('konten.index') }}" class="search-bar" id="searchForm">
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Cari judul atau pembuat..."
                value="{{ request('search') }}"
                autocomplete="off"
            >
            @if(request('search'))
            <button type="button" class="search-clear" onclick="clearSearch()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            @endif
        </div>

        <div class="filter-group">
            <button type="button" class="filter-btn {{ request('status') == 'all' || !request('status') ? 'active' : '' }}" onclick="setFilter('')">
                Semua
            </button>
            <button type="button" class="filter-btn filter-published {{ request('status') == 'published' ? 'active' : '' }}" onclick="setFilter('published')">
                <span class="status-dot" style="background:#10B981"></span>
                Published
            </button>
            <button type="button" class="filter-btn filter-draft {{ request('status') == 'draft' ? 'active' : '' }}" onclick="setFilter('draft')">
                <span class="status-dot" style="background:#F59E0B"></span>
                Draft
            </button>
            <input type="hidden" name="status" id="statusInput" value="{{ request('status') }}">
        </div>
    </form>

    {{-- Aktif filter badge --}}
    @if(request('search') || request('status'))
    <div class="active-filters">
        <span class="af-label">Filter aktif:</span>
        @if(request('search'))
            <span class="af-badge">
                🔍 "{{ request('search') }}"
                <a href="{{ route('konten.index', array_merge(request()->except('search'), ['status' => request('status')])) }}" class="af-remove">×</a>
            </span>
        @endif
        @if(request('status'))
            <span class="af-badge">
                Status: {{ ucfirst(request('status')) }}
                <a href="{{ route('konten.index', array_merge(request()->except('status'), ['search' => request('search')])) }}" class="af-remove">×</a>
            </span>
        @endif
        <a href="{{ route('konten.index') }}" class="af-clear">Hapus semua</a>
    </div>
    @endif

    @if($konten->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        @if(request('search') || request('status'))
            <h3>Tidak ada hasil</h3>
            <p>Tidak ada konten yang cocok dengan pencarian kamu.</p>
            <a href="{{ route('konten.index') }}" class="btn btn-ghost">Tampilkan Semua</a>
        @else
            <h3>Belum ada konten</h3>
            <p>Mulai buat konten pertama Anda sekarang.</p>
            <a href="{{ route('konten.create') }}" class="btn btn-primary">Buat Konten Pertama</a>
        @endif
    </div>
    @else

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-judul">Judul</th>
                    <th class="col-pembuat">Pembuat</th>
                    <th class="col-status">Status</th>
                    <th class="col-tanggal">Tanggal Publikasi</th>
                    <th class="col-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($konten as $index => $item)
                <tr class="table-row" data-id="{{ $item->id }}">
                    <td class="col-no">
                        <span class="row-num">{{ $konten->firstItem() + $index }}</span>
                    </td>
                    <td class="col-judul">
                        <div class="judul-cell">
                            <span class="judul-text">{{ $item->judul }}</span>
                            <span class="judul-preview">{{ Str::limit($item->isi, 60) }}</span>
                        </div>
                    </td>
                    <td class="col-pembuat">
                        <div class="pembuat-cell">
                            <div class="pembuat-avatar">{{ strtoupper(substr($item->pembuat, 0, 2)) }}</div>
                            <span>{{ $item->pembuat }}</span>
                        </div>
                    </td>
                    <td class="col-status">
                        <div class="status-dropdown-wrap">
                            <button class="status-badge {{ $item->status_badge_class }}" onclick="toggleStatusDropdown(this)">
                                <span class="status-dot"></span>
                                {{ $item->status_label }}
                                <svg class="status-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="status-dropdown">
                                @foreach(['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
                                    @if($value !== $item->status)
                                    <form action="{{ route('konten.ubahStatus', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $value }}">
                                        <button type="submit" class="status-option status-option-{{ $value }}">
                                            <span class="status-dot"></span>{{ $label }}
                                        </button>
                                    </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </td>
                    <td class="col-tanggal">
                        @if($item->tanggal_publikasi)
                            <div class="date-cell">
                                <span class="date-main">{{ $item->tanggal_publikasi->format('d M Y') }}</span>
                                <span class="date-time">{{ $item->tanggal_publikasi->format('H:i') }}</span>
                            </div>
                        @else
                            <span class="date-empty">—</span>
                        @endif
                    </td>
                    <td class="col-aksi">
                        <div class="action-group">
                            <a href="{{ route('konten.edit', $item->id) }}" class="action-btn action-edit" title="Edit">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <button class="action-btn action-delete" title="Hapus" onclick="openDeleteModal('{{ $item->id }}', '{{ addslashes($item->judul) }}')">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINASI --}}
    @if($konten->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan <strong>{{ $konten->firstItem() }}–{{ $konten->lastItem() }}</strong>
            dari <strong>{{ $konten->total() }}</strong> konten
        </div>
        <div class="pagination-links">
            @if($konten->onFirstPage())
                <span class="page-btn page-btn-disabled"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
            @else
                <a href="{{ $konten->previousPageUrl() }}" class="page-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
            @endif

            @foreach($konten->getUrlRange(1, $konten->lastPage()) as $page => $url)
                @if($page == $konten->currentPage())
                    <span class="page-btn page-btn-active">{{ $page }}</span>
                @elseif(abs($page - $konten->currentPage()) <= 2 || $page == 1 || $page == $konten->lastPage())
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @elseif(abs($page - $konten->currentPage()) == 3)
                    <span class="page-btn page-btn-dots">…</span>
                @endif
            @endforeach

            @if($konten->hasMorePages())
                <a href="{{ $konten->nextPageUrl() }}" class="page-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
            @else
                <span class="page-btn page-btn-disabled"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
            @endif
        </div>
    </div>
    @endif

    @endif
</div>

@endsection

@push('scripts')
<script>
function setFilter(val) {
    document.getElementById('statusInput').value = val;
    document.getElementById('searchForm').submit();
}
function clearSearch() {
    const form = document.getElementById('searchForm');
    form.querySelector('[name="search"]').value = '';
    form.submit();
}
// Auto-submit search setelah 500ms berhenti mengetik
let searchTimer;
document.querySelector('.search-input')?.addEventListener('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500);
});
</script>
@endpush