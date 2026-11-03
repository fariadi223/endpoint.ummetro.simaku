<?php

namespace App\Helpers;


use App\Models\Prodi\TblSmsModel;
use App\Models\Ta\TblSmtModel;
use Carbon\Carbon;

class ComponentHelper {

    public static function semesterKe($mulai, $thnsmt = null)
	{
		$smtId = (is_null($thnsmt)) ? ComponentHelper::smtAktive() : ['id_smt' => $thnsmt];
        $smact = $smtId['id_smt'];
		$thn   = (int)substr($smact,0,4) - (int)substr($mulai,0,4); 
		$semz  = substr($smact,4,1);
		$semx  = substr($mulai,4,1);
		$hasil = null;

		if($semz == '1') 
		{
			$hasil = ( $thn * 2 ) + 1;

			if($semx == '2')
				$hasil = $thn * 2;
		} 

		if($semz == '2') 
		{
			$hasil = $thn * 2 + 2;

			if($semx == '2')
				$hasil = ( $thn * 2 ) + ($semx - 1);
		}

		if($hasil < 0 ) 
			$hasil = 0;
		
		return $hasil;
	}

    public static function smtAktive($id = null)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        try {
            $smt = TblSmtModel::whereDate('tgl_selesai', '>=', $dateNow)
                              ->whereDate('tgl_mulai', '<=', $dateNow)
                              ->first();
            if(is_null($smt)) {
                return (object) [
                    'id_smt' => '20232',
                    'id_thn_ajaran' => '2023',
                    'smt' => '1',
                    'nm_smt' => '',
                    'thn_akad' => '',
                    'tgl_mulai' => Carbon::now()->format('Y-m-d'),
                    'tgl_selesai'  => Carbon::now()->format('Y-m-d')
                ];
            }

            return $smt;
        }
        catch (\Exception $e) {
            return (object) [
                'id_smt' => '20232',
                'id_thn_ajaran' => '2023',
                'smt' => '',
                'nm_smt' => '',
                'thn_akad' => '',
                'tgl_mulai' => Carbon::now()->format('Y-m-d'),
                'tgl_selesai'  => Carbon::now()->format('Y-m-d')
            ];
        }
    }
    
    public static function smtAktiveId($id = null)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        try {
            $smt = TblSmtModel::where('id_smt',$id)->first();
            if(is_null($smt)) {
                return (object) [
                    'id_smt' => '20232',
                    'id_thn_ajaran' => '2023',
                    'nm_smt' => '',
                    'thn_akad' => '',
                    'tgl_mulai' => Carbon::now()->format('Y-m-d'),
                    'tgl_selesai'  => Carbon::now()->format('Y-m-d')
                ];
            }

            return $smt;
        }
        catch (\Exception $e) {
            return (object) [
                'id_smt' => '20232',
                'id_thn_ajaran' => '2023',
                'smt' => '',
                'nm_smt' => '',
                'thn_akad' => '',
                'tgl_mulai' => Carbon::now()->format('Y-m-d'),
                'tgl_selesai'  => Carbon::now()->format('Y-m-d')
            ];
        }
    }

    public static function listProdi()
    {
        return TblSmsModel::with('jurusan.fakultas')
                            ->where('stat_prodi', 'A')
                            ->orderBy('jenjang_didik', 'DESC')
                            ->get();
    }

    public static function listTahunAkademik()
    {
        return TblSmtModel::orderBy('id_smt', 'DESC')->get();
    }

    public static function listTahun()
    {
        return TblSmtModel::where('smt', '1')->orderBy('id_smt', 'DESC')->get();
    }

    public static function profilMhs($data = [])
    {
        $keys = [];
        
        $roleMhs = \Illuminate\Support\Facades\Auth::guard('mahasiswa');
        if($roleMhs->check()) {
            $data['nipd'] = (isset($data['nipd'])) ? $data['nipd'] : $roleMhs->user()->username;
        
        }
        if(isset($data['nipd'])) {
            $keys[] = ['nipd', '=', $data['nipd']];
        }

        try {
            $profil = \App\Models\Mahasiswa\TblMhsModel::where($keys)->with('prodi.jurusan.fakultas')->with('prodi.satuanpendidikan')->firstOrFail();
        }
        catch (\Exception $e) {
            return [];
        }
        return [
            'id_reg_pd' => $profil->id_reg_pd,
            'id_sms' => $profil->id_sms,
            'mulai_smt' => $profil->mulai_smt,
            'nipd' => $profil->nipd,
            'nm_pd' => $profil->nm_pd,
            'program_studi' => $profil->prodi->nm_lemb,
            'jenjang_didik' => $profil->prodi->jenjang_didik,
            'kurikulum' => $profil->mk_kur,
            'nm_sp' => $profil->prodi->satuanpendidikan->sp_nama,
            'tahun_akademik' => '',
            'semester_ke' => ''
        ];
    }

    public static function krsMhs($data = [])
    {
        $keys = [];
        $mhsKey = [];
        /*
        $roleMhs = \Illuminate\Support\Facades\Auth::guard('mahasiswa');

        if($roleMhs->check()) {
            $data['nipd'] = (isset($data['nipd'])) ? $data['nipd'] : $roleMhs->user()->username;
        }
        */
        if(isset($data['nipd'])) {
            $mhsKey[] = ['nipd', '=', $data['nipd']];
        }
        
        if(isset($data['id_smt'])) {
            $keys[] = ['id_smt', '=', $data['id_smt']];
        }

        $getKrs = \App\Models\Mahasiswa\TblNilModel::where($keys)
                ->with('kurikulum.prodi')
                ->whereHas('mahasiswa', function($query) use ($mhsKey) {
                    $query->where($mhsKey);
                })
                ->limit(100)->get();

        if(empty($getKrs)) {
            return array();
        }

        $result = [
            'jumlah_mk' => 0,
            'jumlah_sks' => 0
        ];

        foreach ($getKrs as $krs) {
            $result['jumlah_mk'] = $result['jumlah_mk'] + 1;
            $result['jumlah_sks'] = $result['jumlah_sks'] + $krs->kurikulum->sks_mk;
        }
        $result['kurikulum'] = $getKrs;
        return $result;
    }
    
    public static function traMhs($data = [])
    {
        $keys = [
            ['nilai_huruf', '!=', 'E'],
            ['nilai_huruf', '<>', ' '],
            ['nilai_huruf', '!=', NULL]
        ];
        $mhsKey = [];
        /*
        $roleMhs = \Illuminate\Support\Facades\Auth::guard('mahasiswa');

        if($roleMhs->check()) {
            $data['nipd'] = (isset($data['nipd'])) ? $data['nipd'] : $roleMhs->user()->username;
        }
        */
        if(isset($data['nipd'])) {
            $mhsKey[] = ['nipd', '=', $data['nipd']];
        }
        
        if(isset($data['id_reg_pd'])) {
            $mhsKey[] = ['id_reg_pd', '=', $data['id_reg_pd']];
        }

        $getKrs = \App\Models\Mahasiswa\TblNilModel::where($keys)
                ->with('kurikulum.prodi')
                ->whereHas('mahasiswa', function($query) use ($mhsKey) {
                    $query->where($mhsKey);
                })
                ->limit(100)->get();

        if(empty($getKrs)) {
            return array();
        }

        $result = [
            'jumlah_mk' => 0,
            'jumlah_sks' => 0
        ];

        foreach ($getKrs as $krs) {
            
            if(empty($krs['nilai_huruf'])) {
                continue;
            }
            
            
            $result['jumlah_mk'] = $result['jumlah_mk'] + 1;
            $result['jumlah_sks'] = $result['jumlah_sks'] + $krs->kurikulum->sks_mk;
        }
        $result['ipk'] = ComponentHelper::nilIPK($getKrs);
        $result['mapel'] = $getKrs;
        
        return $result;
    }

    public static function nilHuruf($nilai)
    {
        /*
        if ($nil_1 <= 100 ) { $huruf = 'A';  }	// 80,00 -- 100,00
		if ($nil_1 < 79.5 ) { $huruf = 'A-'; }	// 76,00 --	 79,00
		if ($nil_1 < 76.5 ) { $huruf = 'B+'; }	// 71,00 --	 75,00
		if ($nil_1 < 72.5 ) { $huruf = 'B';  }	// 70,00
		if ($nil_1 < 68.5 ) { $huruf = 'B-'; }	// 65,00 --	 69,00
		if ($nil_1 < 64.5 ) { $huruf = 'C+'; }	// 65,00 --	 69,00
		if ($nil_1 < 60.5 ) { $huruf = 'C';  }	// 60
		if ($nil_1 < 56.5 ) { $huruf = 'C-'; }	// 55,00 --	 59,00
		if ($nil_1 < 52.5 ) { $huruf = 'D';  }	// 50,00 --	 54,00
		if ($nil_1 < 48.5 ) { $huruf = 'E';  }	// 50
        */
        switch ($nilai) {
            case ($nilai < 48.5) :
                return 'E';
                break;
            case ($nilai < 52.5) :
                return 'D';
                break;
            case ($nilai < 56.5) :
                return 'C-';
                break;
            case ($nilai < 60.5) :
                return 'C';
                break;
            case ($nilai < 64.5) :
                return 'C+';
                break;
            case ($nilai < 68.5) :
                return 'B-';
                break;
            case ($nilai < 72.5) :
                return 'B';
                break;
            case ($nilai < 76.5) :
                return 'B+';
                break;
            case ($nilai < 79.5) :
                return 'A-';
                break;
            case ($nilai < 100) :
                return 'A';
                break;
            default:
                return 'E';
                break;
        }
    }
    
    public static function nilIPS($nilai)
    {
        $i = 0; $jum_amk = 0; $jum_sks = 0; $jum_amkz = 0;
		if(!empty($nilai)) {
			foreach ($nilai as $nil) {
				$sks	= $nil->kurikulum->sks_mk;
				$indeks	= (isset($nil->indeknilai->nilai_indeks)) ? $nil->indeknilai->nilai_indeks : null;
				if ($indeks != null)  {
					$jum_amkz = $indeks * $sks; 
					$jum_amk  = $jum_amkz + $jum_amk;
				}
				$jum_sks = $sks+$jum_sks;
			}
			$hasil = round($jum_amk/$jum_sks, 2);
			return $hasil;
		}
		return 0;
    }
    
    public static function nilIPK($nilai)
    {
        $i = 0; $jum_amk = 0; $jum_sks = 0; $jum_amkz = 0;
		if(!empty($nilai)) {
			foreach ($nilai as $nil) {
			    
			   if(empty($nil->nilai_huruf)) {
                   continue;
               }
                
                
				$sks	= $nil->kurikulum->sks_mk;
				$indeks	= (isset($nil->indeknilai->nilai_indeks)) ? $nil->indeknilai->nilai_indeks : null;
				if ($indeks != null)  {
					$jum_amkz = $indeks * $sks; 
					$jum_amk  = $jum_amkz + $jum_amk;
				}
				$jum_sks = $sks+$jum_sks;
			}
			$hasil = round($jum_amk/$jum_sks, 2);
			return $hasil;
		}
		return 0;
    }
    
}