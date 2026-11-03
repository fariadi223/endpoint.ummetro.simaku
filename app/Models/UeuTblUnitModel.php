<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblUnitModel extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_unit';
    protected $primaryKey   = 'idrek1';
    
    public $timestamps      = false;

    protected $fillable     = [
        'id_unit', 'kode', 'kode_fak', 'nama', 'nama_pimp', 'an_nama_pimp', 'nama_operator', 'jabatan_operator', 'nip', 'pelaksana', 'pagu1', 'pagu2', 'pagu3', 'namauser', 'pass', 'pass_opr', 'status', 'visi', 'misi', 'tu', 'tk', 'ket', 'latblk1', 'latblk2', 'latblk3', 'latblk4', 'unit', 'jml_spp', 'insert_anggaran'
    ];
    
}