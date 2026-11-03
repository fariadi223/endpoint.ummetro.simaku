<?php

namespace App\Http\Requests\spm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Traits\ResponseTrait;

class JournalSpmStore extends FormRequest
{
    use ResponseTrait;
    
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            /*'user' => 'array:name,username',*/
            'debet_records' => 'required|array',
            'kredit_records' => 'required|array',
        ];
    }
    
    /**
     * Custom validation message
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'debet_records.required'  => 'Debet tidak boleh kosong',
            'kredit_records.required' => 'Kredit tidak boleh kosong',
        ];
    }
    
    public function prepareForValidation()
    {
        $debetRecord = collect($this->debet_records);
        $kreditRecord = collect($this->kredit_records);
        $debetValues = $debetRecord->map(function($item, $key) {
                            return [
                                "id_uk"         => (isset($item["id_uk"]))      ? $item["id_uk"]        : 0,
                                "idkeg"         => (isset($item["idkeg"]))      ? $item["idkeg"]        : 0,
                                "kodeunit"      => (isset($item["kodeunit"]))   ? $item["kodeunit"]     : 0,
                                "idtama"        => (isset($item["idtama"]))     ? $item["idtama"]       : 0,
                                "idrek6"        => (isset($item["idrek6"]))     ? $item["idrek6"]       : 0,
                                "idrinci"       => (isset($item["idrinci"]))    ? $item["idrinci"]      : 0,
                                "idrinci_na"    => (isset($item["idrinci_na"])) ? $item["idrinci_na"]   : 0,
                                "tgl_trk"       => (isset($item["tgl_trk"]))    ? $item["tgl_trk"]      : Carbon::now()->format('Y-m-d'),
                                "uraian"        => (isset($item["uraian"]))     ? $item["uraian"]       : 0,
                                "bukti"         => (isset($item["bukti"]))      ? $item["bukti"]        : "",
                                "debet"         => (isset($item["debet"]))      ? $item["debet"]        : 0,
                                "kredit"        => (isset($item["kredit"]))     ? $item["kredit"]       : 0,
                                "ket"           => (isset($item["ket"]))        ? $item["ket"]          : "",
                                "tgl_trans"     => (isset($item["tgl_trans"]))  ? $item["tgl_trans"]    : Carbon::now()->format('d-m-Y'),
                                "key_bank"      => (isset($item["key_bank"]))   ? $item["key_bank"]     : "",
                                "id_impor"      => (isset($item["id_impor"]))   ? $item["id_impor"]     : 0,
                            ];
                       })->toArray();
        $kreditValues = $kreditRecord->map(function($item, $key) {
                            return [
                                "id_uk"         => (isset($item["id_uk"]))      ? $item["id_uk"]        : 0,
                                "idkeg"         => (isset($item["idkeg"]))      ? $item["idkeg"]        : 0,
                                "kodeunit"      => (isset($item["kodeunit"]))   ? $item["kodeunit"]     : 0,
                                "idtama"        => (isset($item["idtama"]))     ? $item["idtama"]       : 0,
                                "idrek6"        => (isset($item["idrek6"]))     ? $item["idrek6"]       : 0,
                                "idrinci"       => (isset($item["idrinci"]))    ? $item["idrinci"]      : 0,
                                "idrinci_na"    => (isset($item["idrinci_na"])) ? $item["idrinci_na"]   : 0,
                                "tgl_trk"       => (isset($item["tgl_trk"]))    ? $item["tgl_trk"]      : Carbon::now()->format('Y-m-d'),
                                "uraian"        => (isset($item["uraian"]))     ? $item["uraian"]       : 0,
                                "bukti"         => (isset($item["bukti"]))      ? $item["bukti"]        : "",
                                "debet"         => (isset($item["debet"]))      ? $item["debet"]        : 0,
                                "kredit"        => (isset($item["kredit"]))     ? $item["kredit"]       : 0,
                                "ket"           => (isset($item["ket"]))        ? $item["ket"]          : "",
                                "tgl_trans"     => (isset($item["tgl_trans"]))  ? $item["tgl_trans"]    : Carbon::now()->format('d-m-Y'),
                                "key_bank"      => (isset($item["key_bank"]))   ? $item["key_bank"]     : "",
                                "id_impor"      => (isset($item["id_impor"]))   ? $item["id_impor"]     : 0,
                            ];
                       })->toArray();
        $this->merge([
            'debet_records' => $debetValues,
            'kredit_records' => $kreditValues
        ]);
    }
    
    protected function failedValidation(Validator $validator)
    {
        $errors     = (new ValidationException($validator))->errors();
        $errorsMsg  = '';
        
        foreach ($errors as $field => $error) :
            $errorsMsg .= implode(' ', $error) .". \n";
        endforeach;
    
        throw new HttpResponseException(
            $this->responseError($errors, $errorsMsg)
        );
    }
    
}