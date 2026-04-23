<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ContentEase') — ContentEase</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/contentease.css') }}">

    @stack('styles')
</head>
<body>

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="logo-text">ContentEase</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu</div>
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               data-tooltip="Dashboard">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                <span>Dashboard</span>
            </a>
            <div class="nav-section-label">Menu</div>
            <a href="{{ route('konten.index') }}"
               class="nav-item {{ request()->routeIs('konten.index') ? 'active' : '' }}"
               data-tooltip="Daftar Konten">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                <span>Daftar Konten</span>
            </a>
            <a href="{{ route('konten.create') }}"
               class="nav-item {{ request()->routeIs('konten.create') ? 'active' : '' }}"
               data-tooltip="Tambah Konten">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                <span>Tambah Konten</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar">MS</div>
                <div class="user-info">
                    <span class="user-name">Muhamad Sahal</span>
                    <span class="user-role">XI RPL B</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- ── MAIN CONTENT ── --}}
    <div class="main-wrapper" id="mainWrapper">

        {{-- Top Navbar --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="topbar-menu-btn" id="topbarMenuBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <div class="breadcrumb">
                    <span class="breadcrumb-root">ContentEase</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    <span class="breadcrumb-current">@yield('breadcrumb', 'Dashboard')</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-time" id="topbarTime"></div>
                <div class="topbar-badge">
                    <span class="pulse-dot"></span>
                    Online
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success" id="flashAlert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error" id="flashAlert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ session('error') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        @endif

        {{-- Page Content --}}
        <main class="page-content">
            @yield('content')
        </main>

        <footer class="page-footer">
            <span>ContentEase &copy; {{ date('Y') }}</span>
            <span>·</span>
            <span>Muhamad Sahal Nurjamil — XI RPL B</span>
        </footer>
    </div>

    {{-- ── DELETE MODAL (global) ── --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon modal-icon-danger">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
            </div>
            <h3 class="modal-title">Hapus Konten?</h3>
            <p class="modal-desc">Anda akan menghapus konten:<br><strong id="deleteItemTitle" class="modal-item-name"></strong></p>
            <p class="modal-warning">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-actions">
                <button class="btn btn-ghost" onclick="closeDeleteModal()">Batal</button>
                <form id="deleteForm" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Main JS --}}
    <script src="{{ asset('js/contentease.js') }}"></script>
    @stack('scripts')
</body>
</html>