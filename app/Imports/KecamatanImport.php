<?php

namespace App\Imports;

use App\Models\Kecamatan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class KecamatanImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Kecamatan([
            'id'        => (int) $row[0],
            'regency_id' => (int) $row[1],
            'name' => (string) $row[2],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
