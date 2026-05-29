<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPosyanduWarga extends Model
{
    protected $table = 'jadwal_posyandu_warga';

    protected $fillable = [
        'jadwal_posyandu_id',
        'warga_id',
        'status',
        'notes',
    ];

    public function jadwalPosyandu()
    {
        return $this->belongsTo(JadwalPosyandu::class, 'jadwal_posyandu_id');
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }
}
