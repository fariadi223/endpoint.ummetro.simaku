<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ResponseTrait;

use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PaginateCollection;

use App\Models\UeuTblRek1Model;
use App\Models\UeuTblKasirModel;

use RequestFilterHelper;

class BkkController extends Controller
{
    use ResponseTrait;
    
    public function index(
        PaginateRequest $request
    ) {
        try {
            $filterField   = [
                'tahun' => 'tahun',
                'idunit' => 'id_unit',
                'no_spm' => 'no_spm'
            ];
            $idunit = $request->input('idunit');
            $unitId = (!is_array($idunit))
                            ? [$idunit] 
                            : ( !empty($idunit) ? $idunit  :  null  );
                            
            $search     = $request->input('search');
            $whereValue = RequestFilterHelper::fieldKey($filterField , $request->all());
            $paginate   = UeuTblKasirModel::where($whereValue)
                                          ->orderBy('id_kasir', 'asc')
                                          ->paginate($request->input('limit'));
                
            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function bkkUnit(
        PaginateRequest $request
    ) {
        return response()->json([], 200);
    }
}