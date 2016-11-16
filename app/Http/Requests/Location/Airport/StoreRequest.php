<?php

namespace App\Http\Requests\Location\Airport;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'airport' => [
                Rule::exists('locations', 'id'),
                'not_in:' . user()->location()->id,
                'has_cash:300',
            ],
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        user()
            ->take('cash', 300)
            ->update('location_id', $this->airport)
            ->updateTime('flying', Carbon::now()->addMinutes(2));
    }
}
