<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BooksRequest extends FormRequest
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
        return [
            'title' => 'required|max:255|min:1',
            'isbn' => 'required|regex:/[0-9]{3}-[0-9]{1}-[0-9]{2}-[0-9]{6}-[0-9]{1}/',
            'image' => 'sometimes|image',
            'author' => 'required|exists:authors,id',
            'genre' => 'required|exists:genres,id',
            'publisher' => 'required|exists:publishers,id',
            'published' => 'sometimes|date_format:d-m-Y',
            'description' => 'required'
        ];
    }
}
