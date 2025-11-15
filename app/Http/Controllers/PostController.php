<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Hamcrest\Core\IsNull;
use Illuminate\Http\Request;

class PostController extends Controller
{
    function index() {
        // $allPosts = [
        //     ['id' => 1, 'title' => 'First Post', 'desc' => 'First Desc', 'posted_by' => 'Admin', 'created_at' => '2024-01-01'],
        //     ['id' => 2, 'title' => 'Second Post', 'desc' => 'Second Desc', 'posted_by' => 'Editor', 'created_at' => '2024-02-01'],
        // ];
        $postsFromDB = Post::all(); // collection object
        // dd($postsFromDB);
        return view('posts.index', ['posts' => $postsFromDB]);
    }

    // function show($postId) {
    function show(Post $post) {
        // $singlePost = ['id' => 1, 'title' => 'First Post', 'desc' => 'First Desc', 'posted_by' => 'Admin', 'created_at' => '2024-01-01'];

          // $singlePostFromDb = Post::find($postId);
        // if (is_null($singlePostFromDb)) {
        //     // return abort(404);
        //     return to_route('posts.index');
        // }

        // $singlePostFromDb = Post::findOrFail($postId);

        // $singlePostFromDb = Post::where('id', $postId)->first();
        // $singlePostFromDb = Post::where('id', $postId)->get();

        // return view('posts.show', ['post' => $singlePostFromDb]);
        return view('posts.show', ['post' => $post]);

    }

    function create() {
        $users = User::all();
        return view('posts.create', ['users' => $users]);
    }

    function store(Request $request) {

        // Validate the request data
        request()->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'post_creator' => 'required|exists:users,id',
        ]);

        // $data = $_POST;

        // $data = request()->all();
        // return $data;

        $title = request()->title;
        $desc = request()->description;
        $post_creator = request()->post_creator;

        // First Way
        // $post = new Post;
        // $post->title = $title;
        // $post->description = $desc;
        // // $post->user_id = $post_creator;
        // $post->save(); // insert into posts (title, description) values (...)

        // Second Way
        Post::create([
            'title' => $title,
            'description' => $desc,
            'user_id' => $post_creator,
            'xyz' => 'some value' // will be ignored due to $fillable in Post model
        ]);

        // Validate and store the post data
        // For now, just redirect back to the posts index
        return redirect()->route('posts.index');
    }

    // function edit($postId) {
    function edit(Post $post) {
        // $singlePost = ['id' => 3, 'title' => 'First Post', 'desc' => 'First Desc', 'posted_by' => 'Admin', 'created_at' => '2024-01-01'];
        $users = User::all();
        return view('posts.edit', ['post' => $post, 'users' => $users]);
    }

    function update(Request $request, $postId) {
        $singlePostFromDb = Post::findOrFail($postId);

        $id = request()->id;
        $title = request()->title;
        $desc = request()->description;
        $post_creator = request()->post_creator;

        $singlePostFromDb->update([
            'title' => $title,
            'description' => $desc,
            'user_id' => $post_creator,
        ]);

        return redirect()->route('posts.show', $postId);
    }

    function destroy($postId) {
        // $post->delete();

        // Post::where('id', $postId)->delete();
        $post = Post::findOrFail($postId);
        $post->delete();

        return redirect()->route('posts.index');
    }
}
