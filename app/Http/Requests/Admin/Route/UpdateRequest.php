<?php

namespace App\Http\Requests\Admin\Route;

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
        $route = $this->route('route');

        return [
            'title'  => 'required|min:2|max:100',
            'menu'   => [
                'required_if_menuable:' . $route->id,
                'exists:menus,id',
            ],
            'url'    => [
                'required',
                Rule::unique('routes')->ignore($route->id)->where('method', $route->method),
                'route_bindings:' . $route->id,
            ],
            'group'  => [
                'required',
                Rule::exists('groups', 'id'),
            ],
            'jail'   => 'required|boolean',
            'flying' => 'required|boolean',
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $route = $this->route('route');

        $route->update([
            'title'   => $this->title,
            'menu_id' => $this->menu,
            'url'     => $this->url,
        ]);

        $route->rules()->update([
            'group_id'      => $this->group,
            'jail_viewable' => $this->jail,
            'fly_viewable'  => $this->flying,
        ]);

        // Because we have dynamic routes loaded from the database,
        // it's a very good practice to use the route cache option.
        Artisan::call('route:cache');
    }
}
