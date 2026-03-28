<?php

namespace App\Rules;

use App\Models\Post;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxPostsRule implements ValidationRule
{
    public function __construct(protected mixed $postId = null)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Post::where('user_id', $value);

        if ($this->postId) {
            $query->where('id', '!=', $this->postId);
        }

        if ($query->count() >= 3) {
            $fail('The user is only allowed to have 3 posts.');
        }
    }
}
