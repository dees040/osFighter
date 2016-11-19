<?php

namespace App\Http\Requests\Crime\Admin;

use App\Models\Crime;
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
            'title' => 'required|min:2|max:255',
            'image' => 'required|image',
            'chance' => 'required|numeric|min:1',
            'max_chance' => 'required|digits_between:1,100',
            'min_payout' => 'required|numeric|min:1',
            'max_payout' => 'required|numeric|min:' . ($this->min_payout + 1),
        ];
    }

    /**
     * Persist the request.
     *
     * @return Crime
     */
    public function persist()
    {
        $crime = Crime::create(
            $this->only('title', 'chance', 'max_chance', 'min_payout', 'max_payout')
        );

        $crime->storeImage($this->file('image'));

        return $crime;
    }
}
