<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblRinciModel extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_rinci';
    protected $primaryKey   = 'idrinci';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idrinci',
        'tahun',
        'idkeg',
        'idrek6',
        'uraianrinci',
        'satuan1',
        'hargasat',
        'ref',
        'qty1',
        'qty2',
        'qty3',
        'satuan2',
        'satuan3',
        'satuan4', 
        'qty4',
        'uk'
    ];
    
    public function rek6()
    {
        return $this->hasOne(UeuTblRek6Model::class, 'idrek6', 'idrek6');
    }
}