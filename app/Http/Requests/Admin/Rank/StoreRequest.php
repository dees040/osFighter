<?php

namespace App\Http\Requests\Admin\Rank;

use App\Models\Rank;
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
            'ranks.*' => 'min:2|max:100',
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        Rank::truncate();

        foreach ($this->ranks as $key => $rank) {
            if ($rank) {
                Rank::create([
                    'name'  => $rank,
                    'level' => $key + 1,
                ]);
            }
        }
    }
}
