<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblJurnalSpmModel extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_jurnal_spm';
    protected $primaryKey   = 'id_trans';
    
    public $timestamps      = false;

    protected $fillable     = [
        'id_trans',
        'tahun', 
        'id_uk', 
        'kodeunit', 
        'idkeg', 
        'idtama', 
        'id_sumber', 
        'id_mak', 
        'idrek6', 
        'idrinci', 
        'idrinci_na', 
        'tgl_trk', 
        'uraian', 
        'bukti', 
        'debet', 
        'kredit', 
        'ket', 
        'tgl_trans', 
        'key_bank', 
        'id_impor'
    ];
    
}