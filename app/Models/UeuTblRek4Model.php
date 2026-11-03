<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblRek4Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_rek4';
    protected $primaryKey   = 'idrek4';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idrek4', 'idrek3', 'koderek4', 'namarek4', 'deleted_at'
    ];
    
    public function rek3() {
        return $this->hasOne(UeuTblRek3Model::class, 'idrek3', 'idrek3');
    }
}