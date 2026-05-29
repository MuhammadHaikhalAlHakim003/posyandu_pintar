<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = ['user_id','key','name','is_active','expires_at'];

    public static function generate(int $userId, string $name): self {
        return self::create([
            'user_id' => $userId,
            'key'     => Str::random(64),
            'name'    => $name,
        ]);
    }

    public function user() { return $this->belongsTo(User::class); }
}