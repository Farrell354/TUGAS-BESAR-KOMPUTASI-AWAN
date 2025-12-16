<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

protected $fillable = [
        'user_id', 'tambal_ban_id', 'nama_pemesan',
        'nomer_telepon', 'alamat_lengkap', 'jenis_kendaraan',
        'keluhan', 'status', 'alasan_batal',
        'latitude', 'longitude'
    ];

    public function tambalBan()
    {
        return $this->belongsTo(TambalBan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chats()
{
    return $this->hasMany(Chat::class);
}
}
