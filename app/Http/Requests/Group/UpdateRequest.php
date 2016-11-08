<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name'  => [
                'required',
                Rule::unique('groups')->ignore($this->route('group')->id),
            ],
            'child' => [
                'required',
                'exists:groups,id',
                'not_in:' . $this->route('group')->id,
            ],
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $this->route('group')->update([
            'name'     => $this->name,
            'child_id' => $this->child,
        ]);
    }
}
