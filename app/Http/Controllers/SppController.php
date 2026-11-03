<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Traits\ResponseTrait;

use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PaginateCollection;

use App\Models\UeuTblSppModel;
use App\Models\UeuTblUnitModel;

use RequestFilterHelper;

class SppController extends Controller
{
    use ResponseTrait;
    
    public function index(
        PaginateRequest $request
    ) {
        try {
            $idunit = $request->input('idunit');
            $unitId = (!is_array($idunit))
                            ? [$idunit] 
                            : ( !empty($idunit) ? $idunit  :  null  );
            $filterField   = [
                'idkeg' => 'idkeg',
                'tahun' => 'tahun'
            ];
            $search     = $request->input('search');
            $keyKeg     = ['unit' => $unitId];
            $whereValue = RequestFilterHelper::fieldKey($filterField , $request->all());
            
            $paginate = UeuTblSppModel::where(function ($query) use ($search) {
                            $query->where('no_spp', 'LIKE', "%{$search}%")
                                  ->orWhere('no_spm', 'LIKE', "%{$search}%");
                        })
                        ->where(function ($query) use ($keyKeg) {
                            $query->whereHas('kegiatan', function ($q) use ($keyKeg) {
                              if(!empty($keyKeg['unit'])) {
                                $q->whereIn('idunit', $keyKeg['unit']);
                              }
                            });
                        })
                        ->where($whereValue)
                        ->orderBy('id_spp', 'asc')
                        ->paginate($request->input('limit'));

            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function showByIdWithRinci(
        PaginateRequest $request,
        $id
    ) {
        $keyKeg = null;
        $record = UeuTblSppModel::where(function ($query) use ($keyKeg) {
                            $query->whereHas('kegiatan', function ($q) use ($keyKeg) {
                              if(!empty($keyKeg['unit'])) {
                                $q->whereIn('idunit', $keyKeg['unit']);
                              }
                            });
                        })
                        ->with('kegiatan.mak4.mak3.mak2.mak1')
                        ->with('kegiatan.unit')
                        
                        ->with('kegiatan.rinci.rek6.rek5.rek4.rek3.rek2.rek1')
                        ->with(['kegiatan' => function($n) {
                                $n->with(['jumlah' => function($k) {
                                    $k->select(
                                        'idkeg', 
                                        DB::raw('SUM(hargasat * qty1 * IF(qty2 = 0, 1, `qty2`) * IF(qty3 = 0, 1, `qty3`) * IF(qty4 = 0, 1, `qty4`)) as anggaran'));
                                    $k->groupBy('idkeg');
                                }]);
                        }])
                        ->where('id_spp', $id)
                        ->first();
            return $this->responseSuccess($record, '', Response::HTTP_OK);
    }
    
    public function spm(
        PaginateRequest $request,
    ) {
        try {
            $filterField   = [
                'idkeg' => 'idkeg',
                'tahun' => 'tahun'
            ];
            $search = $request->input('search');
            $idunit = $request->input('idunit');
            $unitId = (!is_array($idunit))
                            ? [$idunit] 
                            : ( !empty($idunit) ? $idunit  :  null  );
            $whereValue = RequestFilterHelper::fieldKey($filterField , $request->all());
            $keyKeg     = ['unit' => $unitId];

            $paginate = UeuTblSppModel::select(
                            'no_spm',
                            'tgl_spm',
                            DB::raw('count(no_spp) as total'), 
                            DB::raw('sum(ajuan) as ajuan'),
                            DB::raw('SUBSTR(no_spm, 7, 5) as unit_kode1'),
                            DB::raw('SUBSTR(no_spm, 8, 5) as unit_kode2'),
                            DB::raw('SUBSTR(no_spm, 9, 5) as unit_kode3')
                        )
                        ->addSelect(['unit' => UeuTblUnitModel::select('nama as unit_nama')->whereColumn('kode', '=', 'unit_kode1')->orWhereColumn('kode', '=', 'unit_kode2')->orWhereColumn('kode', '=', 'unit_kode3')])
                        ->where(function ($query) use ($search) {
                            $query->where('no_spp', 'LIKE', "%{$search}%")
                                  ->orWhere('no_spm', 'LIKE', "%{$search}%");
                        })
                        ->where(function ($q) use ($keyKeg) {
                            $q->where('no_spm','<>','');
                            $q->whereHas('kegiatan', function ($q) use ($keyKeg) {
                              if(!empty($keyKeg['unit'])) {
                                $q->whereIn('idunit', $keyKeg['unit']);
                              }
                            });
                        })
                        ->where($whereValue)
                        ->with(['spp' => function($q) {
                            $q->withSum(['bkkkegiatan' => function ($query) {
                                $query->whereColumn('idkeg', 'idkeg');
                            }], 'ajuan');
                            $q->with(['kegiatan' => function($n) {
                                $n->select('uraiankeg', 'idkeg');
                                $n->with(['rinci' => function($k) {
                                    $k->select(
                                        'idkeg', 
                                        // DB::Raw("IF(qty2 = 0, 1, `qty2`) as tqty2"),
                                        // DB::Raw("IF(qty3 = 0, 1, `qty3`) as tqty3"),
                                        // DB::Raw("IF(qty4 = 0, 1, `qty4`) as tqty4"),
                                        DB::raw('SUM(hargasat * qty1 * IF(qty2 = 0, 1, `qty2`) * IF(qty3 = 0, 1, `qty3`) * IF(qty4 = 0, 1, `qty4`)) as anggaran'));
                                    $k->groupBy('idkeg');
                                }]);
                            }]);
                           
                        }])
                        ->groupBy('no_spm', 'tgl_spm')
                        ->orderBy('id_spp', 'asc')
                        ->paginate($request->input('limit'));

            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}