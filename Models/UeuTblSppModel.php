<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UeuTblSppModel extends Model
{
    use HasFactory;

    protected $table        = 'ueu_tbl_spp';
    protected $primaryKey   = 'idrinci';
    
    public $timestamps      = false;

    protected $fillable     = [
        'id_spp',
        'tahun',
        'idta',
        'idkeg',
        'ajuan',
        'no_spp',
        'no_spm',
        'tgl_saji',
        'create_spp',
        'otorisasi',
        'alasan1',
        'status_spp',
        'status_spm',
        'alasan2',
        'verifikasi1',
        'alasan3',
        'verifikasi2',
        'alasan4',
        'jurnalspm',
        'tgl_spm',
        'lpj',
        'paraf_jabatan',
        'paraf_nama'
    ];
    
    public function spp()
    {
        return $this->hasMany(UeuTblSppModel::class, 'no_spm', 'no_spm');
    }
    
    public function bkkkegiatan()
    {
        return $this->hasMany(UeuTblSppModel::class, 'idkeg', 'idkeg');
    }
    
    public function kegiatan()
    {
        return $this->hasOne(UeuTblKegModel::class, 'idkeg', 'idkeg');
    }
    
    
    
}