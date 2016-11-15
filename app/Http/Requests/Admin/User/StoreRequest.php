<?php

namespace App\Http\Requests\Admin\User;

use App\Events\AdminCreatedUser;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Notifications\UserWithNewAccount;
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
            'username' => [
                'required',
                'alpha_num',
                Rule::unique('users', 'username'),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
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
            'notify' => 'required|boolean',
        ];
    }

    /**
     * Persist the request.
     *
     * @return User
     */
    public function persist()
    {
        $user = User::create(array_map('strtolower', $this->only('group_id', 'username', 'email')));

        $user->info()->update($this->only('cash', 'bank', 'power', 'rank_id'));

        if ($this->notify) {
            event(new AdminCreatedUser($user));
        }

        return $user;
    }
}
