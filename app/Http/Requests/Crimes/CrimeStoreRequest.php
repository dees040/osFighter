<?php

namespace App\Http\Requests\Crimes;

use Carbon\Carbon;
use App\Models\Crime;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CrimeStoreRequest extends FormRequest
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
            'g-recaptcha-response' => 'required|recaptcha',
            'crime'                => [
                'required',
                Rule::exists('crimes', 'id'),
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
        $crime = Crime::findOrFail($this->crime);

        $crimeSucceededRate = mt_rand(1, 100);

        if ($crimeSucceededRate <= $crime->userChance()) {
            $payout = game()->crimePayout($crime);

            user()->add([
                'cash'           => $payout,
                'crime_progress' => mt_rand(1, 3),
            ]);

            user()->updateTime('crime', Carbon::now()->addSeconds(60));

            return redirect()->route('crime.create')
                ->with('m_success', 'You have successful stolen ' . money($payout));
        } else if ($crimeSucceededRate < ($crime->userChance() + 30)) {
            user()->add('crime_progress', mt_rand(1, 3));
            user()->updateTime('crime', Carbon::now()->addSeconds(60));

            return redirect()->route('crime.create')
                ->with('m_warning', 'You failed the crime, but escaped the police. Cool down for 60 seconds.');
        } else {
            user()->updateTime('jail', Carbon::now()->addSeconds(120));
            user()->updateTime('crime', Carbon::now()->addSeconds(60));

            return redirect()->route('crime.create')
                ->with('m_danger', 'The police caught you. You\'re in jail for 120 seconds.');
        }
    }
}
