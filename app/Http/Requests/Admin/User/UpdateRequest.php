<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
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
            'username' => [
                'required',
                'alpha_num',
                Rule::unique('users', 'username')->ignore($this->route('user')->id),
            ],
            'cash' => 'required|numeric|min:0',
            'bank' => 'required|numeric|min:0',
            'power' => 'required|numeric|min:0',
            'rank_id' => [
                'required',
                Rule::exists('ranks', 'id'),
            ],
            'group_id' => [
                'required',
                Rule::exists('groups', 'id'),
            ],
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $user = $this->route('user');

        $user->update(array_map('strtolower', $this->only('group_id', 'username')));

        $user->info()->update($this->only('cash', 'bank', 'power', 'rank_id'));
    }
}
