<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebutuhanNilai extends Model
{
    use HasFactory;
    protected $table = 'tb_kebutuhan_nilai';

    protected $fillable = [
        'id_mapel',
        'id_dudi',
        'nilai',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }

    public function dudi()
    {
        return $this->belongsTo(TempatDudi::class, 'id_dudi');
    }

}
