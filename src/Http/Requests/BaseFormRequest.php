<?php

namespace ZhenMu\LaravelInitTemplate\Http\Requests;

class BaseFormRequest extends \Illuminate\Foundation\Http\FormRequest
{
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
        $method = sprintf('%sRules', $this->route()->getActionMethod());

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]) ?? [];
        }

        return [];
    }

    public function messages()
    {
        $method = sprintf('%sMessages', $this->route()->getActionMethod());

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]) ?? [];
        }

        return [];
    }
}
