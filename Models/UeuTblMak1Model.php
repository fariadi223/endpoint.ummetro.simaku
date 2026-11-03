<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblMak1Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_mak1';
    protected $primaryKey   = 'idmak1';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idmak1', 'kodemak1', 'namamak1'
    ];
    
}