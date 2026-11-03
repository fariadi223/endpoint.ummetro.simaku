<?php

namespace App\Http\Requests\authentications;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

use App\Traits\ResponseTrait;

class LoginRequest extends FormRequest
{
    use ResponseTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username'    => 'required',
            'password' => 'required|min:3',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Custom validation message
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'username.required'    => 'Please give your email',
            'password.required' => 'Please give your password',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors     = (new ValidationException($validator))->errors();
        $errorsMsg  = '';
        
        foreach ($errors as $field => $error) :
            $errorsMsg .= implode(' ', $error) .". \n";
        endforeach;
    
        throw new HttpResponseException(
            $this->responseError(null, $errorsMsg)
        );
    }
}