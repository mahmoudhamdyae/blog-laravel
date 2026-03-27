<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255|min:3',
            'description' => 'required',
            'post_creator' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a title',
            'title.max' => 'Title must be at most 255 characters',
            'title.min' => 'Title must be at least 3 characters',
            'description.required' => 'Please enter a description',
            'post_creator.required' => 'Please select a post creator',
            'post_creator.exists' => 'Please select a valid post creator',
        ];
    }
}
