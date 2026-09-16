<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Pengaduan extends Model
{
    use SoftDeletes;

    protected $table = 'pengaduan';

    protected $guarded = [];

    protected $casts = [
        'eviden_path' => 'array',
        'tidan_lanjut_path' => 'array',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * Relationship to KategoriPengaduan
     */
    public function kategoriPengaduan(): BelongsTo
    {
        return $this->belongsTo(KategoriPengaduan::class, 'kategori_pengaduan_id');
    }

    /**
     * Relationship to Gedung
     */
    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }

    /**
     * Relationship to Ruang
     */
    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    /**
     * Relationship to User (Creator)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship to User (Assigned To)
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Relationship to User (Escalated To)
     */
    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }

    /**
     * Auto-generate nomor tiket
     * Format: TKT-YYYYMMDD-XXXX
     */
    public static function generateNomorTiket()
    {
        $date = Carbon::now()->format('Ymd');
        $prefix = 'TKT-' . $date . '-';
        
        $lastTicket = self::where('nomor_tiket', 'like', $prefix . '%')
            ->orderBy('nomor_tiket', 'desc')
            ->first();

        if ($lastTicket) {
            $lastNumber = intval(substr($lastTicket->nomor_tiket, -4));
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Status list:
     * 1 = Pembuatan Pengaduan / Belum Diverifikasi (default)
     * 2 = Sedang Diverifikasi (dibuka oleh Bagian Umum dan Perlengkapan)
     * 3 = Tindak Lanjut (diterima oleh Bagian Umum dan Perlengkapan)
     * 4 = Eskalasi (di-assign ke Wakil Dekan)
     * 5 = Verifikasi Eskalasi (Wakil Dekan memberikan jawaban)
     * 6 = Selesai / Diterima
     * 7 = Ditolak oleh Bagian Umum dan Perlengkapan
     */
    public static function getStatusList(): array
    {
        return [
            1 => 'Belum Diverifikasi',
            2 => 'Sedang Diverifikasi',
            3 => 'Tindak Lanjut',
            4 => 'Eskalasi',
            5 => 'Verifikasi Eskalasi',
            6 => 'Selesai / Diterima',
            7 => 'Ditolak',
        ];
    }

    public static function getStatusLabel(int $status): string
    {
        return self::getStatusList()[$status] ?? 'Tidak Diketahui';
    }

    public static function getStatusBadge(int $status): string
    {
        $map = [
            1 => 'bg-secondary',
            2 => 'bg-info',
            3 => 'bg-primary',
            4 => 'bg-warning text-dark',
            5 => 'bg-warning text-dark',
            6 => 'bg-success',
            7 => 'bg-danger',
        ];
        $class = $map[$status] ?? 'bg-secondary';
        $label = self::getStatusLabel($status);
        return '<span class="badge rounded-pill ' . $class . '">' . $label . '</span>';
    }

    /**
     * Accessor: status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusLabel((int) $this->status);
    }

    /**
     * Accessor: status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        return self::getStatusBadge((int) $this->status);
    }
}
