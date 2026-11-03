<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\spm\JournalSpmStore;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PaginateCollection;

use App\Models\UeuTblKegModel;
use App\Traits\ResponseTrait;

use RequestFilterHelper;

class KegiatanController extends Controller
{
    use ResponseTrait;
    
    public function index(
        PaginateRequest $request,
    ) {
        try {
            $filterField   = [
                'tahun' => 'tahun'
            ];
            $idunit = $request->input('idunit');
            $unitId = (!is_array($idunit))
                            ? [$idunit] 
                            : ( !empty($idunit) ? $idunit  :  null  );
                            
            $search     = $request->input('search');
            $whereValue = RequestFilterHelper::fieldKey($filterField , $request->all());

            $paginate = UeuTblKegModel::where(function ($query) use ($search) {
                            $query->where('uraiankeg', 'LIKE', "%{$search}%")
                                  ->orWhere('latblk', 'LIKE', "%{$search}%");
                        })
                        ->where(function ($query) use ($unitId) {
                            if(!empty($unitId))
                                $query->whereIn('idunit', $unitId);
                        })
                        ->withSum(['rinci' => function ($query) {
                                $query->select(
                                        DB::raw('SUM(hargasat * qty1 * IF(qty2 = 0, 1, `qty2`) * IF(qty3 = 0, 1, `qty3`) * IF(qty4 = 0, 1, `qty4`)) as anggaran'));
                                $query->whereColumn('idkeg', 'idkeg');
                        }], 'anggaran')
                        ->where($whereValue)
                        ->orderBy('idkeg', 'asc')
                        ->paginate($request->input('limit'));

            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function showByIdWithRinci( Request $request, $id ) {
        try {
            $data = UeuTblKegModel::with('rinci')->find($id);
            return $this->responseSuccess($data, 'Ok !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function spp(
        PaginateRequest $request,
    ) {
        try {
            $filterField   = [
                'tahun' => 'tahun'
            ];
            $idunit  = $request->input('idunit');
            $unitId  = (!is_array($idunit))
                            ? [$idunit] 
                            : ( !empty($idunit) ? $idunit  :  null  );
                            
            $search     = $request->input('search');
            $whereValue = RequestFilterHelper::fieldKey($filterField , $request->all());

            $paginate = UeuTblKegModel::where(function ($query) use ($search) {
                            $query->where('uraiankeg', 'LIKE', "%{$search}%")
                                  ->orWhere('latblk', 'LIKE', "%{$search}%");
                        })
                        ->where(function ($query) use ($unitId) {
                            if(!empty($unitId))
                                $query->whereIn('idunit', $unitId);
                        })
                        ->withSum(['rinci' => function ($query) {
                                $query->select(
                                        DB::raw('SUM(hargasat * qty1 * IF(qty2 = 0, 1, `qty2`) * IF(qty3 = 0, 1, `qty3`) * IF(qty4 = 0, 1, `qty4`)) as anggaran'));
                                $query->whereColumn('idkeg', 'idkeg');
                        }], 'anggaran')
                        ->withSum(['spp' => function ($query) {
                                $query->select(
                                        DB::raw('SUM(ajuan) as jumlah'));
                                $query->whereColumn('idkeg', 'idkeg');
                                $query->where('status_spm', 'Disetujui');
                        }], 'disppkan')
                        // ->withSum('spp', 'ajuan')
                        ->withCount('spp')
                        ->where($whereValue)
                        ->orderBy('idkeg', 'asc')
                        ->paginate($request->input('limit'));

            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function spm(
        PaginateRequest $request,
    ) {
        try {
            $filterField   = [
                'tahun' => 'tahun'
            ];
            $idunit  = $request->input('idunit');
            $unitId  = (!is_array($idunit))
                            ? [$idunit] 
                            : ( !empty($idunit) ? $idunit  :  null  );
                            
            $search     = $request->input('search');
            $whereValue = RequestFilterHelper::fieldKey($filterField , $request->all());

            $paginate = UeuTblKegModel::where(function ($query) use ($search) {
                            $query->where('uraiankeg', 'LIKE', "%{$search}%")
                                  ->orWhere('latblk', 'LIKE', "%{$search}%");
                        })
                        ->where(function ($query) use ($unitId) {
                            $query->whereHas('spp', function ($q) {
                                $q->where('status_spm', 'Disetujui');
                            });
                            if(!empty($unitId))
                                $query->whereIn('idunit', $unitId);
                        })
                        ->with('spp')
                        ->withSum(['rinci' => function ($query) {
                                $query->select(
                                        DB::raw('SUM(hargasat * qty1 * IF(qty2 = 0, 1, `qty2`) * IF(qty3 = 0, 1, `qty3`) * IF(qty4 = 0, 1, `qty4`)) as anggaran'));
                                $query->whereColumn('idkeg', 'idkeg');
                        }], 'anggaran')
                        ->withSum(['spp' => function ($query) {
                                $query->select(
                                        DB::raw('SUM(ajuan) as jumlah'));
                                $query->whereColumn('idkeg', 'idkeg');
                                $query->where('status_spm', 'Disetujui');
                        }], 'ajuan')
                        // ->withSum('spp', 'ajuan')
                        ->withCount('spp')
                        ->with('mak4')
                        // ->with(['spp' => function($q) {
                        //     $q->where('jurnalspm', '');
                        // }])
                        ->where($whereValue)
                        ->orderBy('idkeg', 'asc')
                        ->paginate($request->input('limit'));

            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}