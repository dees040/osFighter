<?php

namespace App\Http\Requests\Location\Gym;

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
        $strength = mt_rand(1, 15);

        user()
            ->add('strength', $strength)
            ->updateTime('training', Carbon::now()->addMinutes($strength));

        return $strength;
    }
}
