<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblMak3Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_mak3';
    protected $primaryKey   = 'idmak3';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idmak3', 'idmak2', 'kodemak3', 'namamak3'
    ];
    
    public function mak2()
    {
        return $this->hasOne(UeuTblMak2Model::class, 'idmak2', 'idmak2');
    }
}