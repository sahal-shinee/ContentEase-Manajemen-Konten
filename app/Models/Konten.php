<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'konten';

    // Field yang boleh diisi (mass assignment)
    protected $fillable = [
        'judul',
        'isi',
        'status',
        'tanggal_publikasi',
        'pembuat',
    ];

    // Cast tipe data otomatis
    protected $casts = [
        'tanggal_publikasi' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    // ── Scope: filter berdasarkan status ──
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // ── Accessor: label status dalam Bahasa Indonesia ──
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'published' => 'Published',
            'draft'     => 'Draft',
            default     => ucfirst($this->status),
        };
    }

    // ── Accessor: class badge CSS berdasarkan status ──
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'published' => 'badge-published',
            'draft'     => 'badge-draft',
            default     => 'badge-draft',
        };
    }
}