<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    protected $fillable = [
        'id',
        'regency_id',
        'name',
    ];
}
