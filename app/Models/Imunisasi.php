<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Imunisasi extends Model
{
    protected $fillable = [
        'warga_id','kader_id','jenis_imunisasi',
        'tanggal_pemberian','tanggal_berikutnya','keterangan'
    ];

    public function warga() { return $this->belongsTo(Warga::class); }
    public function kader() { return $this->belongsTo(Kader::class); }
}