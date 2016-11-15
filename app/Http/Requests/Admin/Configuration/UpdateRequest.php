<?php

namespace App\Http\Requests\Admin\Configuration;

use Artisan;
use App\Models\Configuration;
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
            'app_name'         => 'required|min:2|max:100',
            'app_slogan'       => 'max:255',
            'user_start_group' => [
                'required',
                Rule::exists('groups', 'id'),
            ],
            'admin_group'      => [
                'required',
                Rule::exists('groups', 'id'),
            ],
            'currency_symbol'  => 'required|in:€,$,£,¥,₹',
            'captcha'          => 'required|in:0,1',
            'timezone'         => 'required|timezone',
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        foreach ($this->except('_method', '_token', 'app_name', 'timezone') as $key => $value) {
            Configuration::where('key', $key)
                ->update(['value' => $value]);
        }

        if ($this->app_name != config('app.name')) {
            Artisan::call('app:set-name', ['name' => $this->app_name]);
            config(['app.name' => $this->app_name]);
        }

        if ($this->timezone != config('app.timezone')) {
            Artisan::call('app:timezone', ['timezone' => $this->timezone]);
            config(['app.timezone' => $this->timezone]);
        }
    }
}
