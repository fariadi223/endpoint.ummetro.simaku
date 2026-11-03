<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblRek6Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_rek6';
    protected $primaryKey   = 'idrek6';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idrek6', 'idrek5', 'koderek6', 'namarek6', 'deleted_at'
    ];
    
    public function rek5() {
        return $this->hasOne(UeuTblRek5Model::class, 'idrek5', 'idrek5');
    }
    
}