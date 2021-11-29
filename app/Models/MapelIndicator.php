<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapelIndicator extends Model
{
    use HasFactory;

    protected $table = 'tb_mapel_indikator';

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }
}
