<?php

namespace App\Http\Requests\Page;

use Artisan;
use Illuminate\Validation\Rule;
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
            'name'  => 'required|min:2|max:100',
            'menu'  => 'required|exists:menus,id',
            'url'   => [
                'required',
                Rule::unique('pages')->ignore($this->route('page')->id),
            ],
            'group' => [
                'required',
                Rule::exists('groups', 'id'),
            ],
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $this->route('page')->update([
            'name'     => $this->name,
            'menu_id'  => $this->menu,
            'url'      => $this->url,
            'group_id' => $this->group,
        ]);

        //Artisan::call('route:cache');
    }
}
