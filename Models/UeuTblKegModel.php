<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblKegModel extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_kegiatan';
    protected $primaryKey   = 'idkeg';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idkeg', 
        'tahun', 
        'idunit', 
        'idunitasal', 
        'idmak1', 
        'idmak4', 
        'uraiankeg', 
        'targetwkt', 
        'idsumberdana', 
        'latblk', 
        'tujuan', 
        'lingkup', 
        'kebijakan_id', 
        'kebijakan_uraian', 
        'jadwal', 
        'ket', 
        'otorisator_review', 
        'created_by', 
        'created_date', 
        'modified_by',
        'modified_date'
    ];
    
    public function rinci() {
        return $this->hasMany(UeuTblRinciModel::class, 'idkeg', 'idkeg');
    }
    public function jumlah() {
        return $this->hasOne(UeuTblRinciModel::class, 'idkeg', 'idkeg');
    }
    public function spp() {
        return $this->hasMany(UeuTblSppModel::class, 'idkeg', 'idkeg');
    }
    public function mak4()
    {
        return $this->hasOne(UeuTblMak4Model::class, 'idmak4', 'idmak4');
    }
    public function unit()
    {
        return $this->hasOne(UeuTblUnitModel::class, 'id_unit', 'idunit');
    }

}