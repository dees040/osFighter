<?php

namespace App\Http\Requests\Location\RLD;

use Carbon\Carbon;
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
            //
        ];
    }

    /**
     * Persist the request.
     *
     * @return int
     */
    public function persist()
    {
        $pimped = mt_rand(2, 15);

        if (is_null(user()->pimped)) {
            user()->updateTime('pimped_cash', Carbon::now());
        }

        user()
            ->add('hoes', $pimped)
            ->updateTime('pimped', Carbon::now()->addMinute(5));

        return $pimped;
    }
}
