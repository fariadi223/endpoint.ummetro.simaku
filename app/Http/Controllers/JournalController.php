<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


use App\Http\Requests\spm\JournalSpmStore;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PaginateCollection;

use App\Models\UeuTblJurnalSpmModel;
use App\Models\UeuTblUnitModel;
use App\Traits\ResponseTrait;

use RequestFilterHelper;

class JournalController extends Controller
{
    use ResponseTrait;

    /**
     * Daftar jurnal SPM.
     * Query: tahun, bukti, key_bank, id_impor, id_rek6, kode_unit, page, limit.
     */
    public function index( PaginateRequest $request ) {
        try {
            // key request => kolom tabel
            $filterField = [
                'tahun'     => 'tahun',
                'bukti'     => 'bukti',
                'key_bank'  => 'key_bank',
                'id_impor'  => 'id_impor',
                'id_rek6'   => 'idrek6',
                'kode_unit' => 'kodeunit',
            ];
            $whereValue = RequestFilterHelper::fieldKey($filterField, $request->all());

            $paginate = UeuTblJurnalSpmModel::where($whereValue)
                        ->orderBy('id_trans', 'desc')
                        ->paginate($request->input('limit'));

            $data = new PaginateCollection($paginate);
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Perbarui unit (id_uk + kodeunit) semua baris jurnal satu transaksi
     * pembayaran: pasangan debet & kredit dengan key_bank + bukti yang sama.
     * kodeunit diturunkan dari kode unit (mis. "03.02" -> 3).
     * Body: key_bank, bukti, id_uk.
     */
    public function updateUnit( Request $request ) {
        $data = $request->validate([
            'key_bank' => ['required', 'string', 'max:100'],
            'bukti'    => ['required', 'string', 'max:100'],
            'id_uk'    => ['required', 'integer'],
        ]);

        try {
            $unit = UeuTblUnitModel::where('id_unit', $data['id_uk'])->first(['id_unit', 'kode', 'nama']);
            if (!$unit) {
                return $this->responseError(null, 'Unit tidak ditemukan.', Response::HTTP_NOT_FOUND);
            }

            $kodeunit = (int) explode('.', trim($unit->kode))[0];
            $jumlah = UeuTblJurnalSpmModel::where('key_bank', $data['key_bank'])
                        ->where('bukti', $data['bukti'])
                        ->update(['id_uk' => (int) $unit->id_unit, 'kodeunit' => $kodeunit]);

            if ($jumlah === 0 && !UeuTblJurnalSpmModel::where('key_bank', $data['key_bank'])->where('bukti', $data['bukti'])->exists()) {
                return $this->responseError(null, 'Jurnal tidak ditemukan.', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess([
                'id_uk'    => (int) $unit->id_unit,
                'kodeunit' => $kodeunit,
                'unit'     => trim($unit->nama),
                'diubah'   => $jumlah,
            ], 'Ok !');

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

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