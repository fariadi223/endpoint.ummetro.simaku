<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


use App\Http\Requests\spm\JournalSpmStore;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PaginateCollection;

use App\Models\UeuTblJurnalSpmModel;
use App\Traits\ResponseTrait;

use RequestFilterHelper;

class JournalController extends Controller
{
    use ResponseTrait;
    
    public function store( JournalSpmStore $request ) {
        try {
            $debet = $request->input('debet_records');
            $kredit = $request->input('kredit_records');
            
            $addDebet = UeuTblJurnalSpmModel::insert($debet);
            $addKredit = UeuTblJurnalSpmModel::insert($kredit);
            
            return $this->responseSuccess([
                'debet' => $addDebet,
                'kredit' => $addKredit
            ], 'Ok !');
            
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}