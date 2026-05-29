<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JadwalPosyanduWarga;

class JadwalPosyandu extends Model
{
    protected $fillable = [
        'tanggal_pelaksanaan',
        'waktu_mulai',
        'waktu_selesai',
        'kategori_posyandu',
        'lokasi',
    ];

    public function assignments()
    {
        return $this->hasMany(JadwalPosyanduWarga::class, 'jadwal_posyandu_id');
    }
}