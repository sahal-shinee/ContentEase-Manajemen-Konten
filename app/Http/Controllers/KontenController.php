<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KontenController extends Controller
{
    /**
     * INDEX — Daftar konten + paginasi + search + filter
     */
    public function index(Request $request)
    {
        $query = Konten::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('pembuat', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['draft', 'published'])) {
            $query->where('status', $request->status);
        }

        $konten = $query->paginate(10)->withQueryString();

        $stats = [
            'total'     => Konten::count(),
            'published' => Konten::where('status', 'published')->count(),
            'draft'     => Konten::where('status', 'draft')->count(),
        ];

        return view('konten.index', compact('konten', 'stats'));
    }

    /**
     * DASHBOARD — Statistik & grafik
     */
    public function dashboard()
    {
        $stats = [
            'total'     => Konten::count(),
            'published' => Konten::where('status', 'published')->count(),
            'draft'     => Konten::where('status', 'draft')->count(),
        ];

        $perBulan = Konten::selectRaw('MONTH(created_at) as bulan, YEAR(created_at) as tahun, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        $bulanLabels = [];
        $bulanData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanLabels[] = $namaBulan[$date->month - 1] . ' ' . $date->year;
            $found = $perBulan->first(fn($item) => $item->bulan == $date->month && $item->tahun == $date->year);
            $bulanData[] = $found ? (int) $found->total : 0;
        }

        $statusData = [
            'labels' => ['Published', 'Draft'],
            'data'   => [$stats['published'], $stats['draft']],
        ];

        $kontenTerbaru = Konten::latest()->take(5)->get();

        $perPembuat = Konten::selectRaw('pembuat, COUNT(*) as total')
            ->groupBy('pembuat')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats','bulanLabels','bulanData','statusData','kontenTerbaru','perPembuat'));
    }

    public function create()
    {
        return view('konten.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'isi'               => 'required|string',
            'status'            => ['required', Rule::in(['draft', 'published'])],
            'tanggal_publikasi' => 'nullable|date',
            'pembuat'           => 'required|string|max:50',
        ], [
            'judul.required'   => 'Judul konten wajib diisi.',
            'isi.required'     => 'Isi konten wajib diisi.',
            'status.required'  => 'Status wajib dipilih.',
            'pembuat.required' => 'Nama pembuat wajib diisi.',
        ]);

        if ($validated['status'] === 'published' && empty($validated['tanggal_publikasi'])) {
            $validated['tanggal_publikasi'] = now();
        }
        if ($validated['status'] === 'draft') {
            $validated['tanggal_publikasi'] = null;
        }

        Konten::create($validated);
        return redirect()->route('konten.index')->with('success', 'Konten berhasil ditambahkan!');
    }

    public function show(Konten $konten)
    {
        return view('konten.show', compact('konten'));
    }

    public function edit(Konten $konten)
    {
        return view('konten.edit', compact('konten'));
    }

    public function update(Request $request, Konten $konten)
    {
        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'isi'               => 'required|string',
            'status'            => ['required', Rule::in(['draft', 'published'])],
            'tanggal_publikasi' => 'nullable|date',
            'pembuat'           => 'required|string|max:50',
        ], [
            'judul.required'   => 'Judul konten wajib diisi.',
            'isi.required'     => 'Isi konten wajib diisi.',
            'status.required'  => 'Status wajib dipilih.',
            'pembuat.required' => 'Nama pembuat wajib diisi.',
        ]);

        if ($validated['status'] === 'published' && empty($validated['tanggal_publikasi'])) {
            $validated['tanggal_publikasi'] = now();
        }
        if ($validated['status'] === 'draft') {
            $validated['tanggal_publikasi'] = null;
        }

        $konten->update($validated);
        return redirect()->route('konten.index')->with('success', 'Konten berhasil diperbarui!');
    }

    public function destroy(Konten $konten)
    {
        $judul = $konten->judul;
        $konten->delete();
        return redirect()->route('konten.index')->with('success', "Konten \"{$judul}\" berhasil dihapus.");
    }

    public function ubahStatus(Request $request, Konten $konten)
    {
        $request->validate(['status' => ['required', Rule::in(['draft', 'published'])]]);

        $konten->status = $request->status;
        if ($request->status === 'published' && !$konten->tanggal_publikasi) {
            $konten->tanggal_publikasi = now();
        }
        if ($request->status === 'draft') {
            $konten->tanggal_publikasi = null;
        }

        $konten->save();
        return redirect()->route('konten.index')
            ->with('success', "Status konten berhasil diubah menjadi {$konten->status_label}.");
    }
}