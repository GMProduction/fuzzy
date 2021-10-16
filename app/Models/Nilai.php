<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;
    protected $table = 'tb_nilai';

    protected $fillable = [
        'id_user',
        'id_mapel',
        'nilai'
    ];

    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }

    public function mapel(){
        return $this->belongsTo(Mapel::class,'id_mapel');
    }

}
