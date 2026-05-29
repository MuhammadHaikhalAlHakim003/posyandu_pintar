<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kader extends Model
{
    protected $fillable = ['user_id','nama_lengkap','no_hp','alamat','wilayah'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function penimbangans() {
        return $this->hasMany(Penimbangan::class);
    }
    public function imunisasis() {
        return $this->hasMany(Imunisasi::class);
    }
}