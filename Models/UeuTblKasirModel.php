<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblKasirModel extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_kasir';
    protected $primaryKey   = 'id_kasir';
    
    public $timestamps      = false;

    protected $fillable     = [
        'id_kasir', 'tahun', 'no_kasir', 'no_spm', 'id_unit', 'tgl_kasir', 'namabank', 'norekbank', 'nobuktirek'
    ];
    
    public function unit()
    {
        return $this->hasOne(UeuTblUnitModel::class, 'id_unit', 'id_unit');
    }

}