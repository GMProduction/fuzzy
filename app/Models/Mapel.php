<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mapel extends Model
{
    use HasFactory;
    protected $table = 'tb_mapel';

    protected $fillable = [
        'nama',
        'alias',
    ];

    public function kebutuhan(){
        return $this->hasOne(KebutuhanNilai::class,'id_mapel')->where('id_dudi','=', Auth::id());
    }
}
