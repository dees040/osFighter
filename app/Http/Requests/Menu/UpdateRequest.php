<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        return [
            'name'     => 'required|min:2|max:100',
            'position' => 'required|in:1,2',
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $this->route('menu')->update($this->only('name', 'position'));
    }
}
