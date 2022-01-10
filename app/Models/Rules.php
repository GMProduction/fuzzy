<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function delete()
    {
        DB::transaction(function (){
            $this->indicator()->delete();
            parent::delete();
        });
    }

}
