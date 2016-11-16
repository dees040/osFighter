<?php

namespace App\Http\Requests\Crimes;

use App\Library\UserHandler;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JailFreeStoreRequest extends FormRequest
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
            'user' => [
                'required',
                Rule::exists('users', 'id'),
            ],
        ];
    }

    /**
     * Persist the request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function persist()
    {
        $userInJail = user(User::findOrFail($this->user));

        if (! $userInJail->isInJail()) {
            return back()
                ->with('m_danger', $userInJail->username . " is not in jail.");
        }

        $secondsInJail = $userInJail->jail->diffInSeconds(\Carbon\Carbon::now());
        $priceToFree = $secondsInJail * 180;

        if (user()->cash < $priceToFree) {
            return back()
                ->with('m_warning', 'You don\'t have ' . money($priceToFree) . ' in cash.');
        }

        $userInJail->updateTime('jail', Carbon::now());
        user()->take('cash', $priceToFree);

        return back()
            ->with('m_success', 'You bought ' . $userInJail->username . ' free.');
    }
}
