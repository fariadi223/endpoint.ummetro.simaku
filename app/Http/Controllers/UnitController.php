<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ResponseTrait;

use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PaginateCollection;

use App\Models\UeuTblUnitModel;

use RequestFilterHelper;

class UnitController extends Controller
{
    use ResponseTrait;

    public function index(
        PaginateRequest $request
    ) {
        try {
            $filterField   = [
                'id_unit' => 'id_unit',
                'kode' => 'kode'
            ];
    
            $search     = $request->input('search');
            $whereValue = RequestFilterHelper::fieldKey($filterField , $request->all());

            $paginate = UeuTblUnitModel::where(function ($query) use ($search) {
                            $query->where('nama', 'LIKE', "%{$search}%")
                                  ->orWhere('kode', 'LIKE', "%{$search}%");
                            
                            
                        })
                        ->where($whereValue)
                        ->orderBy('kode', 'asc')
                        ->paginate($request->input('limit'));

            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}