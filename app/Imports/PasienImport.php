<?php

namespace App\Imports;

use App\Models\Pasien;
use App\Services\LocationService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PasienImport implements ToModel, WithHeadingRow
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $provinsiList = $this->locationService->getProvinsi();

        if ($provinsiList != null) {
            $provinsiId = $this->locationService->findLocationId($row['provinsi'], $provinsiList);
        }
        $kabupatenId = null;
        $kecamatanId = null;
        $desaId = null;
        if ($provinsiId != null) {
            $kabupatenList = $this->locationService->getKabupaten($provinsiId);
            if ($kabupatenList != null) {
                $kabupatenId = $this->locationService->findLocationId($row['kabupatenkota'], $kabupatenList);
            }
        }

        if ($kabupatenId != null) {
            $kecamatanList = $this->locationService->getKecamatan($kabupatenId);
            $kecamatanId = $this->locationService->findLocationId($row['kecamatan'], $kecamatanList);
        }

        if ($kecamatanId != null) {
            $kelurahanList = $this->locationService->getKelurahan($kecamatanId);
            $desaId = $this->locationService->findLocationId($row['desakelurahan'], $kelurahanList);
        }
        $status = null;
        switch ($row['status_kawin']) {
            case 'Belum Menikah':
                $status = '1';
                break;
            case 'Menikah':
                $status = '2';
                break;
            case 'Duda':
                $status = '2';
                break;
            case 'Janda':
                $status = '2';
                break;
            default:
                $status = '1';
                break;
        }
        if ($row['nama_lengkap'] != null) {
            return new pasien([
                'name' => $row['nama_lengkap'],
                'no_rm' => $row['no_rm'],
                'nik' => $row['nik'],
                'jenis_kelamin' => $row['jenis_kelamin'] == 'Laki-Laki' ? 'l' : 'p',
                'alamat' => $row['alamat'],
                'rt' => $row['rt'],
                'rw' => $row['rw'],
                'tanggal_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir']),
                'tempat_lahir' => $row['tempat_lahir'],
                'provinsi_id' => $provinsiId,
                'kabupaten_id' => $kabupatenId,
                'kecamatan_id' => $kecamatanId,
                'desa_id' => $desaId,
                'agama' => $row['agama'],
                'status_kawin' => $status,
                'pendidikan' => $row['pendidikan'],
                'pekerjaan' => $row['pekerjaan'],
                'suku' => $row['suku'],
                'bahasa' => $row['bahasa'],
                'no_hp' => $row['no_handphone'],
                'nama_ortu' => $row['nama_orang_tuapenanggung_jawab'],
                'created_at' => now(),
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
