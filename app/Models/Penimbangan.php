<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Penimbangan extends Model
{
    protected $fillable = [
        'warga_id','kader_id','tanggal','berat_badan',
        'tinggi_badan','lingkar_kepala','status_gizi','catatan',
        'tekanan_darah','lingkar_lengan_atas','lingkar_perut','kolesterol','asam_urat'
    ];

    public function warga() { return $this->belongsTo(Warga::class); }
    public function kader() { return $this->belongsTo(Kader::class); }
}