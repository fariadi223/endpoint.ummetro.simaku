<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblRek3Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_rek3';
    protected $primaryKey   = 'idrek3';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idrek3', 'idrek2', 'koderek3', 'namarek3', 'deleted_at'
    ];
    
    public function rek2() {
        return $this->hasOne(UeuTblRek2Model::class, 'idrek2', 'idrek2');
    }
}