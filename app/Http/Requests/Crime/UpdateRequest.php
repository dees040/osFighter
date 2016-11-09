<?php

namespace App\Http\Requests\Crime;

use Image;
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
            'title' => 'required|min:2|max:255',
            'image' => 'image',
            'chance' => 'required|numeric|min:1',
            'max_chance' => 'required|digits_between:1,100',
            'min_payout' => 'required|numeric|min:1',
            'max_payout' => 'required|numeric|min:' . ($this->min_payout + 1),
        ];
    }

    /**
     * Persist the request.
     */
    public function persist()
    {
        $crime = $this->route('crime');

        $crime->update(
            $this->only('title', 'chance', 'max_chance', 'min_payout', 'max_payout')
        );

        if ($this->hasFile('image')) {
            $crime->deleteImage();

            $image = $this->file('image');
            $path = public_path('images/game/crimes/' . $crime->id . '.jpg');

            Image::make($image)->encode('jpg')->save($path);
        }
    }
}
