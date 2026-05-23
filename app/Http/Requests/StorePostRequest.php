<?php

namespace App\Http\Requests;

use App\Models\Post;
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
        $postId = $this->route('post') instanceof Post
            ? $this->route('post')->id
            : $this->route('post');

        return [
            'title' => 'required|max:255|min:3',
            'title_ar' => 'nullable|max:255|min:3',
            'description' => 'required',
            'description_ar' => 'nullable',
            'post_creator' => [
                'required',
                'exists:users,id',
                new \App\Rules\MaxPostsRule($postId),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('Please enter a title'),
            'title.max' => __('Title must be at most 255 characters'),
            'title.min' => __('Title must be at least 3 characters'),
            'title_ar.max' => __('Title must be at most 255 characters'),
            'title_ar.min' => __('Title must be at least 3 characters'),
            'description.required' => __('Please enter a description'),
            'post_creator.required' => __('Please select a post creator'),
            'post_creator.exists' => __('Please select a valid post creator'),
            'image.image' => __('The uploaded file must be an image'),
            'image.mimes' => __('Allowed image formats are: jpeg, png, jpg, gif, svg'),
            'image.max' => __('The image size must not exceed 2048 KB'),
            'tags.string' => __('Tags must be a string of comma separated values'),
        ];
    }
}
