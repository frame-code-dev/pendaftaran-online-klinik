<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    use HasFactory;
    protected $table = 'poliklinik';
    protected $fillable = [
        'gambar',
        'name',
        'keterangan',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
