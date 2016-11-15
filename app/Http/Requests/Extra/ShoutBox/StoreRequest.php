<?php

namespace App\Http\Requests\Extra\ShoutBox;

use App\Events\ShoutBoxMessageCreated;
use App\Models\ShoutBox;
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
            'g-recaptcha-response' => 'captcha_confirmed',
            'body' => 'required|min:5|max:140|shout_box_limit',
        ];
    }

    /**
     * Persist the request.
     *
     * @return ShoutBox
     */
    public function persist()
    {
        $message = ShoutBox::create([
            'user_id' => $this->user()->id,
            'body'    => $this->body,
        ]);

        broadcast(new ShoutBoxMessageCreated($message))->toOthers();

        return $message;
    }
}
