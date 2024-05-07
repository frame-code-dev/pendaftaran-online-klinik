<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;
    protected $table = 'regencies';
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    protected $fillable = [
        'id',
        'province_id',
        'name',
    ];
}
