<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginateRequest extends FormRequest
{
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
            //
        ];
    }
    
    protected function passedValidation(): void
    { 
        $limit  = ($this->input('limit')) ? $this->input('limit') : 10;
        $length = ($this->input('length')) ? $this->input('length') : $limit;
        $start  = ($this->input('start')) ? $this->input('start') :  -1;
        
        $searchRequest = $this->input('search');
        $searchValue   = (!is_array($searchRequest))
                            ? $searchRequest 
                            : ( isset($searchRequest['value']) ? $searchRequest['value']  :  ''  );
        //$searchValue   = (!is_array($searchRequest)) ? $searchRequest : '';
        $this->merge([
            'page' => ($start > 0) ? floor(($start / $length)) + 1 : 1,
            'start' => $start,
            'limit' => $length,
            'search' => $searchValue
        ]);
        
    }
}
