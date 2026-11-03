<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblRek2Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_rek2';
    protected $primaryKey   = 'idrek2';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idrek2', 'idrek1', 'koderek2', 'namarek2', 'deleted_at'
    ];
    
    public function rek1() {
        return $this->hasOne(UeuTblRek1Model::class, 'idrek1', 'idrek1');
    }
}