<?php

namespace App\Repositories;

interface PostRepositoryInterface
{
    /**
     * Delete posts that are older than the specified number of years.
     *
     * @param int $years
     * @return int The number of deleted posts.
     */
    public function deletePostsOlderThan(int $years): int;
}
