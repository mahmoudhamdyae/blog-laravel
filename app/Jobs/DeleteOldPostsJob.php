<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Foundation\Queue\Queueable;

class DeleteOldPostsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PostRepositoryInterface $postRepository): void
    {
        $postRepository->deletePostsOlderThan(1);
    }
}
