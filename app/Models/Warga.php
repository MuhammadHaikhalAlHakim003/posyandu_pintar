<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\JadwalPosyanduWarga;

class Warga extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'kategori',
        'nama_orang_tua',
        'no_hp',
        'no_hp_wali',
        'alamat',
        'rt_rw',
        'status_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function penimbangans() {
        return $this->hasMany(Penimbangan::class);
    }
    public function imunisasis() {
        return $this->hasMany(Imunisasi::class);
    }
    public function jadwalAssignments()
    {
        return $this->hasMany(JadwalPosyanduWarga::class, 'warga_id');
    }
}