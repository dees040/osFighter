<?php

namespace App\Http\Requests\Family;

use App\Models\Family;
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
            'name' => [
                'required',
                Rule::unique('families', 'name'),
                'regex:/^[a-zA-Z0-9\s-]+$/',
            ],
        ];
    }

    /**
     * Persist the request.
     *
     * @return Family
     */
    public function persist()
    {
        $family = Family::create([
            'user_id' => currentUser()->id,
            'name' => $this->name,
        ]);

        currentUser()->update([
            'family_id' => $family->id,
        ]);

        return $family;
    }
}
