<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ResponseTrait;

use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PaginateCollection;

use App\Models\UeuTblRek1Model;

use RequestFilterHelper;

class RekController extends Controller
{
    use ResponseTrait;

    public function indexRek1(
        PaginateRequest $request,
    ) {
        try {
            $filterField   = [
                'idrek1' => 'idrek1',
                'koderek1' => 'koderek1'
            ];
            $search     = $request->input('search');
            $whereValue = RequestFilterHelper::fieldKey($filterField , $request->all());

            $paginate = UeuTblRek1Model::where(function ($query) use ($search) {
                            $query->where('namarek1', 'LIKE', "%{$search}%")
                                  ->orWhere('koderek1', 'LIKE', "%{$search}%");
                            
                            
                        })
                        ->where($whereValue)
                        ->orderBy('koderek1', 'asc')
                        ->paginate($request->input('limit'));

            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // public function showRek1( Request $request, $id ) {
    //     try {
    //         $data = KurModel::with('prodi')->find($id);
    //         return $this->responseSuccess($data, 'Ok !');
    //     } catch (\Exception $e) {
    //         return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // public function store( Request $request ) {
        
    //     try {
    //         $rows = $request->except(['id']);
    //         $data = KurModel::create($rows);
    //         return $this->responseSuccess($data, 'Ok !');
    //     } catch (\Exception $e) {
    //         return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // public function updateRek1( Request $request, $id ) {
    //     try {
    //         $rows = $request->except(['id']);
    //         $data = KurModel::find($id);
    //         if (is_null($data)) {
    //             return $this->responseSuccess([], 'Ok !', 404);
    //         }

    //         $data->update($rows);
    //         $data = KurModel::find($data->id);
        
    //         return $this->responseSuccess($data, 'Ok !');
    //     } catch (\Exception $e) {
    //         return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // public function destroyRek1(Request $request, $id)
    // {
    //     try {
    //         $data = KurModel::find($id);
    //         if (is_null($data)) {
    //             return $this->responseSuccess([], 'Ok !', 404);
    //         }
    //         $data->delete($data);
    //         return $this->responseSuccess($data, 'Ok !');
    //     } catch (\Exception $e) {
    //         return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

}