<?php

namespace App\Repositories;

use App\Models\Post;
use Carbon\Carbon;

class EloquentPostRepository implements PostRepositoryInterface
{
    /**
     * Delete posts that are older than the specified number of years.
     *
     * @param int $years
     * @return int The number of deleted posts.
     */
    public function deletePostsOlderThan(int $years): int
    {
        // Using delete() which triggers SoftDeletes if the model uses it.
        // To permanently remove them, use forceDelete().
        return Post::where('created_at', '<=', now()->subYears($years))->delete();
    }
}
