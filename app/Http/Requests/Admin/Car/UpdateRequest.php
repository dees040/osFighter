<?php

namespace App\Http\Requests\Admin\Car;

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
            'name'  => 'required|min:2|max:100',
            'image' => 'image|max:5000',
            'price' => 'required|numeric|min:10',
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $car = $this->route('car');

        $car->update($this->only('name', 'price'));

        if ($this->hasFile('image')) {
            $car->deleteImage();

            $car->storeImage($this->file('image'));
        }
    }
}
