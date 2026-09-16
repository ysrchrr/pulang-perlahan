<?php

namespace App\Imports;

use App\Models\Ruang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class RuangImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $gedung_id = $row['id_gedung'] ?? null;

        if (!$gedung_id) {
            return null; // Skip if no gedung id
        }

        return new Ruang([
            'gedung_id'     => $gedung_id,
            'kode_ruang'    => $row['kode_ruang'],
            'nama_ruang'    => $row['nama_ruang'],
            'lantai'        => $row['lantai'],
            'jenis_ruang'   => $row['jenis_ruang'],
            'kapasitas'     => $row['kapasitas'],
            'is_active'     => 1,
            'created_by'    => session('user_id'),
        ]);
    }
}
