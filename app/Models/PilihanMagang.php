<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilihanMagang extends Model
{
    use HasFactory;
    protected $table = 'tb_pilihan_magang';

    protected $fillable = [
        'id_user',
        'id_dudi',
        'urutan',
    ];

    public function dudi()
    {
        return $this->belongsTo(User::class, 'id_dudi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
