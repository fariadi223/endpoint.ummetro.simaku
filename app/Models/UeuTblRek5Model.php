<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblRek5Model extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_rek5';
    protected $primaryKey   = 'idrek5';
    
    public $timestamps      = false;

    protected $fillable     = [
        'idrek5', 'idrek4', 'koderek5', 'namarek5', 'deleted_at'
    ];
    
    public function rek4() {
        return $this->hasOne(UeuTblRek4Model::class, 'idrek4', 'idrek4');
    }
}