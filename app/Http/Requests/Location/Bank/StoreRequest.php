<?php

namespace App\Http\Requests\Location\Bank;

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
            'amount' => 'required|numeric|min:1',
            'option' => 'required|in:withdraw,deposit',
        ];
    }

    public function persist()
    {
        if ($this->option == 'withdraw') {
            if (! user()->hasSupplies('bank', $this->amount)) {
                return back()
                    ->with('m_warning', 'You don\'t have ' . money($this->amount) . ' on your bank balance.');
            }

            user()
                ->add('cash', $this->amount)
                ->take('bank', $this->amount);
        } else {
            if (! user()->hasSupplies('cash', $this->amount)) {
                return back()
                    ->with('m_warning', 'You don\'t have ' . money($this->amount) . ' in cash.');
            }

            user()
                ->add('bank', $this->amount)
                ->take('cash', $this->amount);
        }

        return redirect()->route('bank.create')
            ->with('m_succes', ucfirst($this->option) . ' with the amount of ' . $this->amount . ' successful.');
    }
}
