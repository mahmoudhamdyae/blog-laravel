@extends('layouts.app')

@section('title')
    Edit
@endsection

@section('content')

    <form method="POST" action="{{ route('posts.update', $post['id']) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" type="text" class="form-control" value="{{ $post->title }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $post->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Post Creator</label>
            <select name="post_creator" class="form-control">
                @foreach ($users as $user)
                    <option {{-- @if ($user->id == $post->user_id)
                        selected
                    @endIf --}} @selected($user->id == $post->user_id) value="{{ $user->id }}">
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Post Image</label>
            <input name="image" type="file" class="form-control">
            @if ($post->image)
                <div class="mt-2 text-center">
                    <p>Current Image:</p>

                    @if (Str::startsWith($post->image, ['http://', 'https://']))
                        <img src="{{ $post->image }}" alt="Post Image" style="width: 200px; height: auto;">
                    @else
                        <img src="{{ Storage::url($post->image) }}" alt="Post Image" style="width: 200px; height: auto;">
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Tags (comma separated)</label>
            <input name="tags" type="text" class="form-control" value="{{ old('tags', $post->tags->pluck('name')->implode(', ')) }}" placeholder="e.g. laravel, coding, web">
        </div>

        <button class="btn btn-primary">Update</button>
    </form>


@endsection
