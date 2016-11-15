<?php

namespace App\Http\Requests\Menu\Admin;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     *
     * @return Menu
     */
    public function persist()
    {
        return Menu::create($this->only('name', 'position'));
    }
}
