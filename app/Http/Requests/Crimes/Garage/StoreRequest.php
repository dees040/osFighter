<?php

namespace App\Http\Requests\Crimes\Garage;

use DB;
use App\Models\Car;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * @var string
     */
    protected $route = '';

    /**
     * StoreRequest constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->route = route('garage.index') . '#tab-garage';
    }

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
            'car'                  => [
                'required',
                Rule::exists('car_user', 'id')->where('user_id', currentUser()->id),
            ],
            'action'               => 'required|in:repair,sell',
        ];
    }

    /**
     * Persist the request.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function persist()
    {
        $pivot = DB::table('car_user')->where('id', $this->car)->where('user_id', currentUser()->id)->first();

        if (is_null($pivot)) {
            return redirect($this->route)
                ->with('m_danger', 'This is not your car.');
        }

        $car = Car::find($pivot->car_id);

        if ($this->action == 'sell') {
            $value = $car->price / 100 * (100 - $pivot->damage);

            user()
                ->add('cash', $value);

            DB::table('car_user')->delete($this->car);

            return redirect($this->route)
                ->with('m_success', 'You have sold the ' . $car->name . ' for ' . money($value) . '.');
        } else {
            $repairCost = $car->price / 100 * $pivot->damage;

            if (user()->hasSupplies('cash', $repairCost)) {
                user()->take('cash', $repairCost);

                DB::table('car_user')->where('id', $this->car)->update(['damage' => 0]);

                return redirect(route('garage.index') . '#tab-garage')
                    ->with('m_success', 'Your car has been repaired.');
            }
        }

        return redirect(route('garage.index') . '#tab-garage')
            ->with('m_danger', 'You don\'t have ' . money($repairCost) . ' in cash.');
    }
}
