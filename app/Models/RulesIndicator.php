<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RulesIndicator extends Model
{
    use HasFactory;

    protected $table = 'rules_indicator';

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function rules()
    {
        return $this->belongsTo(Rules::class, 'rule_id');
    }
}
