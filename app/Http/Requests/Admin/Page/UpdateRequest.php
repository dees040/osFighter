<?php

namespace App\Http\Requests\Page\Admin;

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
        $page = $this->route('page');

        return [
            'name'  => 'required|min:2|max:100',
            'menu'  => [
                'required_if_menuable:' . $page->id,
                'exists:menus,id'
            ],
            'url'   => [
                'required',
                Rule::unique('pages')->ignore($page->id)->where('route_method', $page->route_method),
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
