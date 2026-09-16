<?php

namespace App\Imports;

use App\Models\Gedung;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class GedungImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Gedung([
            'nama_gedung'   => $row['nama_gedung'],
            'kode_gedung'   => $row['kode_gedung'],
            'lokasi_kampus' => $row['lokasi_kampus'],
            'alamat'        => $row['alamat'],
            'is_active'     => 1,
            'created_by'    => session('user_id'),
        ]);
    }
}
