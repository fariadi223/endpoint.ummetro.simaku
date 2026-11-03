<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblRek1Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_rek1';
    protected $primaryKey   = 'idrek1';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idrek1', 'koderek1', 'namarek1', 'deleted_at'
    ];
    
}