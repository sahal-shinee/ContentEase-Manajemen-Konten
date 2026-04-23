@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- ── STAT CARDS ── --}}
<div class="stats-grid" style="margin-bottom:28px">
    <div class="stat-card stat-total" style="animation-delay:0s">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="stat-body">
            <span class="stat-label">Total Konten</span>
            <span class="stat-value" data-count="{{ $stats['total'] }}">0</span>
        </div>
    </div>
    <div class="stat-card stat-published" style="animation-delay:0.05s">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="stat-body">
            <span class="stat-label">Published</span>
            <span class="stat-value" data-count="{{ $stats['published'] }}">0</span>
        </div>
    </div>
    <div class="stat-card stat-draft" style="animation-delay:0.1s">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </div>
        <div class="stat-body">
            <span class="stat-label">Draft</span>
            <span class="stat-value" data-count="{{ $stats['draft'] }}">0</span>
        </div>
    </div>
</div>

{{-- ── CHARTS ROW ── --}}
<div class="dash-grid">

    {{-- Line Chart: Konten per bulan --}}
    <div class="dash-card dash-card-wide">
        <div class="dash-card-header">
            <div>
                <h3 class="dash-card-title">Konten per Bulan</h3>
                <p class="dash-card-sub">12 bulan terakhir</p>
            </div>
            <div class="dash-legend">
                <span class="legend-dot" style="background:#4F8EF7"></span>
                <span>Total Konten</span>
            </div>
        </div>
        <div class="chart-wrap">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    {{-- Donut Chart: Status --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div>
                <h3 class="dash-card-title">Status Konten</h3>
                <p class="dash-card-sub">Distribusi saat ini</p>
            </div>
        </div>
        <div class="chart-wrap chart-wrap-donut">
            <canvas id="donutChart"></canvas>
        </div>
        <div class="donut-legend">
            <div class="donut-legend-item">
                <span class="legend-dot" style="background:#10B981"></span>
                <span>Published</span>
                <strong>{{ $stats['published'] }}</strong>
            </div>
            <div class="donut-legend-item">
                <span class="legend-dot" style="background:#F59E0B"></span>
                <span>Draft</span>
                <strong>{{ $stats['draft'] }}</strong>
            </div>
        </div>
    </div>

</div>

{{-- ── BOTTOM ROW ── --}}
<div class="dash-grid" style="margin-top:20px">

    {{-- Konten Terbaru --}}
    <div class="dash-card dash-card-wide">
        <div class="dash-card-header">
            <div>
                <h3 class="dash-card-title">Konten Terbaru</h3>
                <p class="dash-card-sub">5 konten paling baru</p>
            </div>
            <a href="{{ route('konten.index') }}" class="dash-link">Lihat Semua →</a>
        </div>
        @if($kontenTerbaru->isEmpty())
            <div class="dash-empty">Belum ada konten.</div>
        @else
        <div class="recent-list">
            @foreach($kontenTerbaru as $item)
            <div class="recent-item">
                <div class="recent-avatar">{{ strtoupper(substr($item->pembuat, 0, 2)) }}</div>
                <div class="recent-body">
                    <span class="recent-title">{{ Str::limit($item->judul, 45) }}</span>
                    <span class="recent-meta">{{ $item->pembuat }} · {{ $item->created_at->diffForHumans() }}</span>
                </div>
                <span class="status-badge {{ $item->status_badge_class }} recent-badge">
                    <span class="status-dot"></span>
                    {{ $item->status_label }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Top Pembuat --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div>
                <h3 class="dash-card-title">Top Pembuat</h3>
                <p class="dash-card-sub">Berdasarkan jumlah konten</p>
            </div>
        </div>
        @if($perPembuat->isEmpty())
            <div class="dash-empty">Belum ada data.</div>
        @else
        <div class="pembuat-list">
            @php $maxTotal = $perPembuat->first()->total; @endphp
            @foreach($perPembuat as $i => $p)
            <div class="pembuat-item">
                <div class="pembuat-rank">{{ $i + 1 }}</div>
                <div class="pembuat-info">
                    <div class="pembuat-row">
                        <span class="pembuat-name">{{ $p->pembuat }}</span>
                        <span class="pembuat-count">{{ $p->total }} konten</span>
                    </div>
                    <div class="pembuat-bar-wrap">
                        <div class="pembuat-bar" style="width: {{ ($p->total / $maxTotal) * 100 }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Animated counter ──
    document.querySelectorAll('.stat-value[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count);
        if (target === 0) { el.textContent = '0'; return; }
        let current = 0;
        const step = Math.ceil(target / 30);
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(timer);
        }, 40);
    });

    // ── Chart defaults ──
    Chart.defaults.color = '#64748B';
    Chart.defaults.font.family = "'DM Sans', sans-serif";

    const gridColor  = 'rgba(255,255,255,0.05)';
    const accentBlue = '#4F8EF7';
    const accentGreen= '#10B981';
    const accentAmber= '#F59E0B';

    // ── Line Chart ──
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    const gradient = lineCtx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(79,142,247,0.25)');
    gradient.addColorStop(1, 'rgba(79,142,247,0)');

    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: @json($bulanLabels),
            datasets: [{
                label: 'Konten',
                data: @json($bulanData),
                borderColor: accentBlue,
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: accentBlue,
                pointBorderColor: '#111827',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1A2235',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    titleColor: '#F1F5F9',
                    bodyColor: '#94A3B8',
                    padding: 12,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} konten`,
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor },
                    ticks: { font: { size: 11 } }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        font: { size: 11 },
                        stepSize: 1,
                        callback: v => Number.isInteger(v) ? v : null
                    },
                    beginAtZero: true,
                }
            }
        }
    });

    // ── Donut Chart ──
    const total = {{ $stats['total'] }};
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusData['labels']),
            datasets: [{
                data: total > 0 ? @json($statusData['data']) : [1],
                backgroundColor: total > 0
                    ? [accentGreen, accentAmber]
                    : ['rgba(255,255,255,0.06)'],
                borderColor: '#111827',
                borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: total > 0,
                    backgroundColor: '#1A2235',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    titleColor: '#F1F5F9',
                    bodyColor: '#94A3B8',
                    padding: 12,
                }
            }
        },
        plugins: [{
            id: 'centerText',
            beforeDraw(chart) {
                const { ctx, chartArea: { width, height, left, top } } = chart;
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const cx = left + width / 2;
                const cy = top + height / 2;
                ctx.font = 'bold 28px Syne, sans-serif';
                ctx.fillStyle = '#F1F5F9';
                ctx.fillText(total, cx, cy - 10);
                ctx.font = '12px DM Sans, sans-serif';
                ctx.fillStyle = '#64748B';
                ctx.fillText('Total', cx, cy + 14);
                ctx.restore();
            }
        }]
    });

});
</script>
@endpush