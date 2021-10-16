<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatDudi extends Model
{
    use HasFactory;
    protected $table = 'tb_tempat_dudi';

    protected $fillable = [
        'id_user',
        'nama',
        'alamat',
        'foto',
    ];

}
