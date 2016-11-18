<?php

namespace App\Http\Requests\Crimes\Car;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function persist()
    {
        $chance = mt_rand(1, 100);

        if ($chance < 30) {
            $car = user()
                ->add('crime_progress', mt_rand(2, 5))
                ->addRankProgress(mt_rand(1, 3))
                ->addRandomCar();

            $time = ['car'  => Carbon::now()->addMinutes(5)];

            $response = redirect(route('garage.index') . '#tab-garage')
                ->with('m_success', 'You have stolen a ' . $car->name . ' with a damage of ' . $car->pivot->damage . '%.');
        } else if ($chance < 60) {
            $time = ['car'  => Carbon::now()->addMinutes(5)];

            $response = redirect(route('garage.index') . '#tab-cars')
                ->with('m_success', 'You failed to steal a car, but escaped the police.');
        } else {
            $time = [
                'car'  => Carbon::now()->addMinutes(5),
                'jail' => Carbon::now()->addMinute(2),
            ];

            $response = redirect()->route('jail.index')
                ->with('m_danger', 'The police caught you..');
        }

        user()->updateTime($time);

        return $response;
    }
}
