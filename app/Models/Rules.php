<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    use HasFactory;

    protected $table = 'rules';

    public function indicator()
    {
        return $this->hasMany(RulesIndicator::class, 'rule_id');
    }

    public function dudi()
    {
        return $this->belongsTo(TempatDudi::class, 'dudi_id');
    }

}
