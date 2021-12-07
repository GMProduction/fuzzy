<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $table = 'tb_siswa';

    protected $fillable = [
        'id_user',
        'nama',
        'alamat',
        'hp',
        'foto',
        'nim',
    ];

//    public function magang()
//    {
//        return $this->hasMany(PilihanMagang::class, 'id_user')
//    }

}
