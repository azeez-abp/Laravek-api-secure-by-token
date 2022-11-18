<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ResizeImageManipulation extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules  =  [
            //
            'width' => ['required', 'regex:/^\d.+(\.\d+)?%?$/'], // \.\d.+ .123 (%?=> may contain %) /
            'height' => ['required', 'regex:/^\d.+(\.\d+)?%?$/'], // \.\d.+ .123 (%?=> may contain %) /
            // all this must preset in request 
            'album_id' => 'exists:App\Model\Album,id'
        ];

        $image  = $this->all()['image'] ?? false; //$this is request
        //var_dump($image);
        //  var_dump($_FILES);
        if ($image && $image instanceof UploadedFile) {
            $rules['image'][] = 'image';
        } else {
            $rules['image'][] = 'url';
        }
        // var_dump($rules);
        return $rules;
    }
}
