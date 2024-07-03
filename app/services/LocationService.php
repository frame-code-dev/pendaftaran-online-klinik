<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class LocationService
{
    protected $urlProvinsi = 'https://ibnux.github.io/data-indonesia/provinsi.json';
    protected $urlKabupaten = 'https://ibnux.github.io/data-indonesia/kabupaten/';
    protected $urlKecamatan = 'https://ibnux.github.io/data-indonesia/kecamatan/';
    protected $urlKelurahan = 'https://ibnux.github.io/data-indonesia/kelurahan/';

    public function getProvinsi()
    {
        return Http::get($this->urlProvinsi)->json();
    }

    public function getKabupaten($provinsiId)
    {
        return Http::get($this->urlKabupaten . $provinsiId . '.json')->json();
    }

    public function getKecamatan($kabupatenId)
    {
        return Http::get($this->urlKecamatan . $kabupatenId . '.json')->json();
    }

    public function getKelurahan($kecamatanId)
    {
        return Http::get($this->urlKelurahan . $kecamatanId . '.json')->json();
    }

    public function findLocationId($name, $locationArray)
    {
        foreach ($locationArray as $location) {
            if (strtolower($location['nama']) === strtolower($name)) {
                return $location['id'];
            }
        }
        return null;
    }
}

?>
