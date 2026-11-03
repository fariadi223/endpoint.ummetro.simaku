<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblMak4Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_mak4';
    protected $primaryKey   = 'idmak4';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idmak4', 'idmak3', 'kodemak4', 'namamak4', 'borang', 'blj'
    ];

    public function mak3()
    {
        return $this->hasOne(UeuTblMak3Model::class, 'idmak3', 'idmak3');
    }
    
}