<?php

namespace App\Http\Requests\Admin\Car;

use Image;
use App\Models\Car;
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
            'name'  => 'required|min:2|max:100',
            'image' => 'required|image|max:5000',
            'price' => 'required|numeric|min:10',
        ];
    }

    /**
     * Persist the request.
     *
     * @return Car
     */
    public function persist()
    {
        $car = Car::create($this->only('name', 'price'));

        $image = $this->file('image');

        Image::make($image)->encode('jpg')->save(public_path($car->getPath()));

        return $car;
    }
}
