<?php

namespace App\Http\Requests\Admin\Location;

use App\Models\Location;
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
            'locations.*' => 'min:2|max:100',
        ];
    }

    public function persist()
    {
        Location::truncate();

        foreach ($this->locations as $key => $location) {
            if ($location) {
                Location::create([
                    'name'  => $location
                ]);
            }
        }
    }
}
