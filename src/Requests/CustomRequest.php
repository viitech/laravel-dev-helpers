<?php

namespace VIITech\Helpers\Requests;

class CustomRequest extends FormRequest
{
    public $key_path = '';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Messages
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Attributes
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }
}
