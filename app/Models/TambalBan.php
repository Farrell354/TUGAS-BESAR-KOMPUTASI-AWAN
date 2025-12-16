<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TambalBan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_bengkel',
        'gambar',
        'kategori',
        'alamat',
        'nomer_telepon',
        'jam_buka',
        'jam_tutup',
        'latitude',
        'longitude',
        'deskripsi'
    ];

    // Relasi Review
    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }

    // Helper untuk hitung rata-rata rating
    public function getRataRataRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
