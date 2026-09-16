<?php

namespace App\Imports;

use App\Models\KategoriPengaduan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class KategoriImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row['nama_kategori'])) {
            return null;
        }

        return new KategoriPengaduan([
            'nama_kategori' => $row['nama_kategori'],
            'created_by'    => session('user_id'),
        ]);
    }
}
