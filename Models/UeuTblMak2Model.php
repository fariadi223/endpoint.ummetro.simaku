<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblMak2Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_mak2';
    protected $primaryKey   = 'idmak2';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idmak2', 'idmak1', 'kodemak2', 'namamak2'
    ];
    
    public function mak1()
    {
        return $this->hasOne(UeuTblMak1Model::class, 'idmak1', 'idmak1');
    }
}