<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\User;
use Hamcrest\Core\IsNull;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    function index() {
        // $allPosts = [
        //     ['id' => 1, 'title' => 'First Post', 'desc' => 'First Desc', 'posted_by' => 'Admin', 'created_at' => '2024-01-01'],
        //     ['id' => 2, 'title' => 'Second Post', 'desc' => 'Second Desc', 'posted_by' => 'Editor', 'created_at' => '2024-02-01'],
        // ];
        // $postsFromDB = Post::all(); // collection object
        $postsFromDB = Post::paginate(10);
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
        $comments = $post->comments()->paginate(5);
        return view('posts.show', ['post' => $post, 'comments' => $comments]);

    }

    function create() {
        $users = User::all();
        return view('posts.create', ['users' => $users]);
    }

    function store(
        // Request
        StorePostRequest
        $request) {

        // Validate the request data
        // request()->validate([
        //     'title' => 'required|max:255|min:3',
        //     'description' => 'required',
        //     'user_id' => 'required|exists:users,id',
        // ],
        // [
        //     'title.required' => 'Please enter a title',
        //     'title.max' => 'Title must be at most 255 characters',
        //     'title.min' => 'Title must be at least 3 characters',
        //     'description.required' => 'Please enter a description',
        //     'post_creator.required' => 'Please select a post creator',
        //     'post_creator.exists' => 'Please select a valid post creator',
        // ]
        // );



        // $data = $_POST;

        // $data = request()->all();
        // return $data;

        $title = request()->title;
        $desc = request()->description;
        $user_id = request()->user_id;

        // First Way // No need for fillable
        // $post = new Post;
        // $post->title = $title;
        // $post->description = $desc;
        // // $post->user_id = $post_creator;
        // $post->save(); // insert into posts (title, description) values (...)

        // Second Way
        // Post::create([
        //     'title' => $title,
        //     'description' => $desc,
        //     'user_id' => $post_creator,
        //     'xyz' => 'some value' // will be ignored due to $fillable in Post model
        // ]);

        // $data = request()->all();
        // Post::create($data);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        Post::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'user_id' => $data['post_creator'],
            'image' => $data['image'] ?? null,
        ]);
        // Post::create(request()->all());

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

    function update(
        // Request
        StorePostRequest
         $request, $postId) {

    //      request()->validate([
    //         'title' => 'required|max:255|min:3',
    //         'description' => 'required',
    //         'post_creator' => 'required|exists:users,id',
    //     ],
    //     [
    //         'title.required' => 'Please enter a title',
    //         'title.max' => 'Title must be at most 255 characters',
    //         'title.min' => 'Title must be at least 3 characters',
    //         'description.required' => 'Please enter a description',
    //         'post_creator.required' => 'Please select a post creator',
    //         'post_creator.exists' => 'Please select a valid post creator',
    //     ]
    // );

        $singlePostFromDb = Post::findOrFail($postId);

        $id = request()->id;
        $title = request()->title;
        $desc = request()->description;
        $post_creator = request()->post_creator;

        $data = $request->validated();
        $post = Post::findOrFail($postId);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        $post->update([
            'title' => $title,
            'description' => $desc,
            'user_id' => $post_creator,
            'image' => $data['image'] ?? $post->image,
        ]);

        return redirect()->route('posts.show', $postId);
    }

    function destroy($postId) {
        // $post->delete();

        // Post::where('id', $postId)->delete();
        $post = Post::findOrFail($postId);
        // $post->comments()->delete(); // مسح التعليقات أولاً

        // Delete image file if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index');
    }

    function getPostData(Post $post)
    {
        return response()->json([
            'title' => $post->title,
            'description' => $post->description,
            'user_name' => $post->user ? $post->user->name : 'not_found',
            'user_email' => $post->user ? $post->user->email : 'not_found',
        ]);
    }
}
