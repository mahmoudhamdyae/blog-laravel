<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|min:2',
        ]);

        $post->comments()->create([
            'body' => $request->body,
        ]);

        return redirect()->route('posts.show', $post->id);
    }

    function destroy(Comment $comment)
    {
        $post_id = $comment->commentable_id;
        $comment->delete();

        return redirect()->route('posts.show', $post_id);
    }
}
